<?php
class mc360 {

  public $system = 'phoenix';
  public $version = '1.1';

  public $debug = false;

  public $apikey = '';
  public $key_valid = false;
  public $store_id = '';

  public function __construct() {
    $this->apikey = MODULE_HEADER_TAGS_MAILCHIMP_360_API_KEY;
    $this->store_id = MODULE_HEADER_TAGS_MAILCHIMP_360_STORE_ID;
    $this->key_valid = (MODULE_HEADER_TAGS_MAILCHIMP_360_KEY_VALID == 'true');

    if (!Text::is_empty(MODULE_HEADER_TAGS_MAILCHIMP_360_DEBUG_EMAIL)) {
      $this->debug = true;
    }

    $this->validate_cfg();
  }

  public function complain($msg){
    echo '<div style="position:absolute;left:0;top:0;width:100%;font-size:24px;text-align:center;background:#CCCCCC;color:#660000">MC360 Module: '.$msg.'</div><br>';
  }

  public function validate_cfg(){
    $this->valid_cfg = false;
    if (empty($this->apikey)){
      $this->complain('You have not entered your API key. Please read the installation instructions.');
      return;
    }

    if (!$this->key_valid) {
      $GLOBALS["mc_api_key"] = $this->apikey;
      $api = new MCAPI('notused','notused');
      $res = $api->ping();
      if ($api->errorMessage!=''){
        $this->complain('Server said: "'.$api->errorMessage.'". Your API key is likely invalid. Please read the installation instructions.');
        return;
      }

      $this->key_valid = true;
      $GLOBALS['db']->query("update configuration set configuration_value = 'true' where configuration_key = 'MODULE_HEADER_TAGS_MAILCHIMP_360_KEY_VALID'");

      if (empty($this->store_id)){
        $this->store_id = md5(uniqid(rand(), true));
        $GLOBALS['db']->query("update configuration set configuration_value = '" . $this->store_id . "' where configuration_key = 'MODULE_HEADER_TAGS_MAILCHIMP_360_STORE_ID'");
      }
    }

    if (empty($this->store_id)) {
      $this->complain('Your Store ID has not been set. This is not good. Contact support.');
    } else {
      $this->valid_cfg = true;
    }
  }

  public function set_cookies() {
    if (!$this->valid_cfg){
      return;
    }

    $thirty_days = time()+60*60*24*30;
    if (isset($_REQUEST['mc_cid'])){
      setcookie('mailchimp_campaign_id', trim($_REQUEST['mc_cid']), $thirty_days);
    }

    if (isset($_REQUEST['mc_eid'])){
      setcookie('mailchimp_email_id', trim($_REQUEST['mc_eid']), $thirty_days);
    }
  }

  public function process() {
    if (!$this->valid_cfg) {
      return;
    }

    $debug_email = '';

    if ($this->debug) {
      $debug_email .= '------------[New Order ]-----------------' . "\n" .
                      '$_COOKIE =' . "\n" .
                      print_r($_COOKIE, true);
    }

    if (!isset($_COOKIE['mailchimp_campaign_id']) || !isset($_COOKIE['mailchimp_email_id'])){
      return;
    }

    if ($this->debug) {
      $debug_email .= date('Y-m-d H:i:s') . ' current ids:' . "\n" .
                      date('Y-m-d H:i:s') . ' eid =' . $_COOKIE['mailchimp_email_id'] . "\n" .
                      date('Y-m-d H:i:s') . ' cid =' . $_COOKIE['mailchimp_campaign_id'] . "\n";
    }

    $order = $GLOBALS['db']->query("SELECT orders_id FROM orders WHERE customers_id = " . (int)$_SESSION['customer_id'] . " ORDER BY date_purchased DESC LIMIT 1")->fetch_assoc();

    $totals = [];
    $totals_query = $GLOBALS['db']->query("SELECT value, class FROM orders_total WHERE orders_id = " . (int)$order['orders_id']);
    while ($total = $totals_query->fetch_assoc()) {
      $totals[$total['class']] = $total['value'];
    }

    $products = [];
    $products_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT products_id, products_model, products_name, products_tax, products_quantity, final_price
 FROM orders_products
 WHERE orders_id = %d
EOSQL
      , (int)$order['orders_id']));
    while ($product = $products_query->fetch_assoc()) {
      $products[] = [
        'id' => $product['products_id'],
        'name' => $product['products_name'],
        'model' => $product['products_model'],
        'qty' => $product['products_quantity'],
        'final_price' => $product['final_price'],
      ];

      $totals['ot_tax'] += $product['product_tax'];
    }

    $mcorder = [
      'id' => $order['orders_id'],
      'total' => $totals['ot_total'],
      'shipping' => $totals['ot_shipping'],
      'tax' => $totals['ot_tax'],
      'items' => [],
      'store_id' => $this->store_id,
      'store_name' => $_SERVER['SERVER_NAME'],
      'campaign_id' => $_COOKIE['mailchimp_campaign_id'],
      'email_id' => $_COOKIE['mailchimp_email_id'],
      'plugin_id' => 1216,
    ];

    foreach ($products as $product) {
      $item = [];
      $item['line_num'] = $line;
      $item['product_id'] = $product['id'];
      $item['product_name'] = $product['name'];
      $item['sku'] = $product['model'];
      $item['qty'] = $product['qty'];
      $item['cost'] = $product['final_price'];

      //All this to get a silly category name from here
      $cats = $GLOBALS['db']->query("SELECT categories_id FROM products_to_categories WHERE products_id = " . (int)$product['id'] . " LIMIT 1")->fetch_assoc();
      $cat_id = $cats['categories_id'];

      $item['category_id'] = $cat_id;
      $cat_name == '';
      $continue = true;
      do {
//now recurse up the categories tree...
        $cats = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT c.categories_id, c.parent_id, cd.categories_name
 FROM categories c INNER JOIN categories_description cd ON c.categories_id = cd.categories_id
 WHERE c.categories_id = %d
EOSQL
          , (int)$cat_id))->fetch_assoc();
        if ($cat_name == '') {
          $cat_name = $cats['categories_name'];
        } else {
          $cat_name = $cats['categories_name'] . ' - ' . $cat_name;
        }
        $cat_id = $cats['parent_id'];
      } while ($cat_id);
      $item['category_name'] = $cat_name;

      $mcorder['items'][] = $item;
    }

    $GLOBALS["mc_api_key"] = $this->apikey;
    $api = new MCAPI('notused','notused');
    $res = $api->campaignEcommAddOrder($mcorder);
    if ($api->errorMessage!=''){
      if ($this->debug) {
        $debug_email .= 'Error:' . "\n" .
                        $api->errorMessage . "\n";
      }
    } else {
            //nothing
    }
        // send!()

    if ($this->debug && !empty($debug_email)) {
      Notifications::mail('', MODULE_HEADER_TAGS_MAILCHIMP_360_DEBUG_EMAIL, 'MailChimp Debug E-Mail', $debug_email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }

  }

}
