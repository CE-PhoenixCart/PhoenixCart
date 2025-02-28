<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class product_notification {

    public $show_choose_audience = true;
    public $title, $content;

    public function __construct($title, $content) {
      $this->title = $title;
      $this->content = $content;
    }

    public function choose_audience() {
      $products = $GLOBALS['db']->fetch_all(sprintf(<<<'EOSQL'
SELECT pd.products_id AS id, pd.products_name AS text
  FROM products p INNER JOIN products_description pd ON pd.products_id = p.products_id
  WHERE p.products_status = 1 AND pd.language_id = %d
 ORDER BY pd.products_name
EOSQL
        , (int)$_SESSION['languages_id']));

$choose_audience_string = '<script><!--
function mover(move) {
  if (move == \'remove\') {
    for (x=0; x<(document.notifications.products.length); x++) {
      if (document.notifications.products.options[x].selected) {
        with(document.notifications.elements[\'chosen[]\']) {
          options[options.length] = new Option(document.notifications.products.options[x].text,document.notifications.products.options[x].value);
        }
        document.notifications.products.options[x] = null;
        x = -1;
      }
    }
  }
  if (move == \'add\') {
    for (x=0; x<(document.notifications.elements[\'chosen[]\'].length); x++) {
      if (document.notifications.elements[\'chosen[]\'].options[x].selected) {
        with(document.notifications.products) {
          options[options.length] = new Option(document.notifications.elements[\'chosen[]\'].options[x].text,document.notifications.elements[\'chosen[]\'].options[x].value);
        }
        document.notifications.elements[\'chosen[]\'].options[x] = null;
        x = -1;
      }
    }
  }
  return true;
}

function selectAll(FormName, SelectBox) {
  temp = "document." + FormName + ".elements[\'" + SelectBox + "\']";
  Source = eval(temp);

  for (x=0; x<(Source.length); x++) {
    Source.options[x].selected = "true";
  }

  if (x<1) {
    alert(\'' . JS_PLEASE_SELECT_PRODUCTS . '\');
    return false;
  } else {
    return true;
  }
}
//--></script>';

      $link = $GLOBALS['link']->set_parameter('nID', (int)$_GET['nID'])->set_parameter('action', 'confirm');
      $choose_audience_string .= new Form('notifications', $link, 'post', ['onsubmit' => "return selectAll('notifications', 'chosen[]')"]);
        $choose_audience_string .= '<div class="row mb-3">';
          $choose_audience_string .= '<div class="col-5">';
            $choose_audience_string .= '<h6>' . TEXT_PRODUCTS . '</h6>';
            $choose_audience_string .= new Select('products', $products, ['class' => 'form-select', 'size' => '20', 'multiple' => null]);
          $choose_audience_string .= '</div>';
          $choose_audience_string .= '<div class="col-2 align-self-center text-center">';
            $choose_audience_string .= $GLOBALS['Admin']->button(BUTTON_GLOBAL, 'fas fa-globe', 'btn-info', (clone $link)->set_parameter('global', 'true'));
            $choose_audience_string .= '<br><br>';
            $choose_audience_string .= '<input type="button" class="btn btn-secondary" value="' . BUTTON_SELECT . '" onClick="mover(\'remove\');">';
            $choose_audience_string .= '<br><br>';
            $choose_audience_string .= '<input type="button" class="btn btn-secondary" value="' . BUTTON_UNSELECT . '" onClick="mover(\'add\');">';
          $choose_audience_string .= '</div>';
          $choose_audience_string .= '<div class="col-5">';
            $choose_audience_string .= '<h6>' . TEXT_SELECTED_PRODUCTS . '</h6>';
            $choose_audience_string .= new Select('chosen[]', [], ['class' => 'form-select', 'size' => '20', 'multiple' => null]);
          $choose_audience_string .= '</div>';
        $choose_audience_string .= '</div>';

        $choose_audience_string .= '<div class="d-grid mt-2">';
          $choose_audience_string .= new Button(IMAGE_SEND, 'fas fa-paper-plane', 'btn-success btn-lg');
        $choose_audience_string .= '</div>';
        $choose_audience_string .= $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-light mt-1', (clone $link)->delete_parameter('action'));
      $choose_audience_string .= '</form>';

      return $choose_audience_string;
    }

    public function confirm() {
      $sql = "SELECT DISTINCT customers_id FROM products_notifications";
      if ('true' !== ($_GET['global'] ?? null)) {
        $sql .= " WHERE products_id in (" . implode(', ', array_map('intval', $_POST['chosen'])) . ")";
      }

      $audience = array_unique(array_merge(
        array_column($GLOBALS['db']->fetch_all($sql), 'customers_id'),
        array_column($GLOBALS['db']->fetch_all("SELECT customers_info_id FROM customers_info WHERE global_product_notifications = 1"), 'customers_info_id')
        ));

      $confirm_string = '<div class="alert alert-danger">' . sprintf(TEXT_COUNT_CUSTOMERS, count($audience)) . '</div>';

        $confirm_string .= '<table class="table table-striped">';
          $confirm_string .= '<tr>';
            $confirm_string .= '<th scope="row">' . TEXT_TITLE . '</th>';
            $confirm_string .= '<td>' . $this->title . '</td>';
          $confirm_string .= '</tr>';
          $confirm_string .= '<tr>';
            $confirm_string .= '<th scope="row">' . TEXT_CONTENT . '</th>';
            $confirm_string .= '<td>' . $this->content . '</td>';
          $confirm_string .= '</tr>';
        $confirm_string .= '</table>';

      $link = $GLOBALS['link']->set_parameter('nID', (int)$_GET['nID']);
      if (count($audience) > 0) {
        $form = new Form('confirm', (clone $link)->set_parameter('action', 'confirm_send'));
        if (isset($_POST['chosen']) && is_array($_POST['chosen'])) {
          foreach ($_POST['chosen'] as $customer_id) {
            $form->hide('chosen[]', $customer_id);
          }
        } else {
          $form->hide('global', 'true');
        }

        $confirm_string .= $form;
          $confirm_string .= '<div class="d-grid mt-2">';
            $confirm_string .= new Button(IMAGE_SEND, 'fas fa-paper-plane', 'btn-success btn-lg');
          $confirm_string .= '</div>';
        $confirm_string .= '</form>';
      }

      $confirm_string .= $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-light mt-1', $link->set_parameter('action', 'send'));

      return $confirm_string;
    }

    public function send($newsletter_id) {
      global $customer_data;
      $audience = [];

      $db_tables = $customer_data->build_db_tables(['id', 'name', 'email_address'], 'customers');
      Guarantor::guarantee_subarray($db_tables, 'customers');
      $db_tables['customers']['customers_id'] = null;
      $built = Text::rtrim_once(customer_query::build_specified_columns($db_tables), query::COLUMN_SEPARATOR)
             . ' FROM' . customer_query::build_joins($db_tables, []);

      $sql = 'SELECT DISTINCT ' . $built;
      $sql .= ' INNER JOIN products_notifications pn ON c.customers_id = pn.customers_id';
      if ('true' !== ($_POST['global'] ?? null)) {
        $sql .= ' WHERE pn.products_id in (' . implode(',', $_POST['chosen']) . ')';
      }

      $products_query = $GLOBALS['db']->query($sql);
      while ($products = $products_query->fetch_assoc()) {
        $audience[$customer_data->get('id', $products)] = [
          'name' => $customer_data->get('name', $products),
          'email_address' => $customer_data->get('email_address', $products),
        ];
      }

      $customers_query = $GLOBALS['db']->query('SELECT ' . $built . ' INNER JOIN customers_info ci ON c.customers_id = ci.customers_info_id WHERE ci.global_product_notifications = 1');
      while ($customers = $customers_query->fetch_assoc()) {
        $audience[$customer_data->get('id', $customers)] = [
          'name' => $customer_data->get('name', $customers),
          'email_address' => $customer_data->get('email_address', $customers),
        ];
      }

      $mimemessage = new email();
      $mimemessage->add_message($this->content);
      $mimemessage->build_message();

      foreach ($audience as $value) {
        $mimemessage->send($value['name'], $value['email_address'], STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $this->title);
      }

      $newsletter_id = Text::input($newsletter_id);
      $GLOBALS['db']->query("UPDATE newsletters SET date_sent = NOW(), status = 1 WHERE newsletters_id = " . (int)$newsletter_id);
    }

  }
