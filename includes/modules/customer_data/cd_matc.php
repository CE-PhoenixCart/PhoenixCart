<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cd_matc extends abstract_customer_data_module {

    const CONFIG_KEY_BASE = 'MODULE_CUSTOMER_DATA_MATC_';

    const PROVIDES = [ 'matc' ];
    const REQUIRES = [  ];

    protected function get_parameters() {
      return [
        static::CONFIG_KEY_BASE . 'STATUS' => [
          'title' => 'Enable MATC Module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        static::CONFIG_KEY_BASE . 'GROUP' => [
          'title' => 'Customer data group',
          'value' => '6',
          'desc' => 'In what group should this appear?',
          'use_func' => 'customer_data_group::fetch_name',
          'set_func' => 'Config::select_customer_data_group(',
        ],
        static::CONFIG_KEY_BASE . 'PAGES' => [
          'title' => 'Pages',
          'value' => 'create_account',
          'desc' => 'On what pages should this appear?',
          'set_func' => 'Customers::select_pages(',
          'use_func' => 'abstract_module::list_exploded',
        ],
        static::CONFIG_KEY_BASE . 'CHECKOUT' => [
          'title' => 'Checkout Page',
          'value' => 'False',
          'desc' => 'Should the MATC also show on checkout_confirmation?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        static::CONFIG_KEY_BASE . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '6800',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

    public function get($field, &$customer_details) {
      switch ($field) {
        case 'matc':
          if (!isset($customer_details[$field])) {
            $customer_details[$field] = $customer_details['matc'] ?? null;
          }

          return $customer_details[$field];
      }
    }

    public function display_input(&$customer_details = null) {
      $input = new Tickable('matc', ['value' => '1', 'class' => 'form-check-input'], 'checkbox');
      $input->require();

      if ($customer_details && is_array($customer_details)) {
        $input->tick('1' == $this->get('matc', $customer_details));
      }

      include Guarantor::ensure_global('Template')->map(__FILE__);

      $p_modal = info_pages::get_page([
        'p.slug' => 'privacy',
        'pd.languages_id' => $_SESSION['languages_id'],
      ]);
      $modal = [
        'name' => 'PModal',
        'title' => $p_modal['pages_title'],
        'text' => $p_modal['pages_text'],
        'close_button' => MATC_BUTTON_CLOSE,
      ];

      ob_start();
      include Guarantor::ensure_global('Template')->map('modal.php', 'component');

      $GLOBALS['Template']->add_block(ob_get_clean(), 'footer_scripts');

      $c_modal = info_pages::get_page([
        'p.slug' => 'conditions',
        'pd.languages_id' => $_SESSION['languages_id'],
      ]);
      $modal = [
        'name' => 'TCModal',
        'title' => $c_modal['pages_title'],
        'text' => $c_modal['pages_text'],
        'close_button' => MATC_BUTTON_CLOSE,
      ];

      ob_start();
      include Guarantor::ensure_global('Template')->map('modal.php', 'component');

      $GLOBALS['Template']->add_block(ob_get_clean(), 'footer_scripts');
    }

    public function process(&$customer_details) {
      $customer_details['matc'] = isset($_POST['matc']) ? Text::input($_POST['matc']) : false;

      if ('1' !== $customer_details['matc']) {
        $GLOBALS['messageStack']->add_classed($GLOBALS['message_stack_area'] ?? 'customer_data', ENTRY_MATC_ERROR);

        return false;
      }

      return true;
    }

    public function get_template() {
      return Text::ltrim_once(__FILE__, DIR_FS_CATALOG);
    }

    public function hook() {
      if ( 'True' === $this->base_constant('CHECKOUT') && 'True' === $this->base_constant('STATUS') ) { 
        $this->display_input();
      }
    }

    public function is_checked() {
      if (!$this->process()) {
        Form::block_processing();
      }
    }

    public function install($parameter_key = null) {
      parent::install($parameter_key);

      if (is_null($parameter_key)) {
        $GLOBALS['db']->query(<<<'EOSQL'
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method)
 VALUES ('shop', 'checkout_confirmation', 'injectFormDisplay', 'display_matc', 'cd_matc', 'hook')
EOSQL
          );
        $GLOBALS['db']->query(<<<'EOSQL'
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method)
 VALUES ('shop', 'checkout_confirmation', 'injectFormVerify', 'verify_matc', 'cd_matc', 'is_checked')
EOSQL
          );
      }
    }

    public function remove() {
      parent::remove();

      $GLOBALS['db']->query("DELETE FROM hooks WHERE hooks_class = 'cd_matc'");
    }

  }
