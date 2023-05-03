<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cd_email_address extends abstract_customer_data_module {

    const CONFIG_KEY_BASE = 'MODULE_CUSTOMER_DATA_EMAIL_ADDRESS_';

    const PROVIDES = [ 'email_address' ];
    const REQUIRES = [  ];

    protected function get_parameters() {
      return [
        static::CONFIG_KEY_BASE . 'STATUS' => [
          'title' => 'Enable Email Address module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        static::CONFIG_KEY_BASE . 'GROUP' => [
          'title' => 'Customer data group',
          'value' => '1',
          'desc' => 'In what group should this appear?',
          'use_func' => 'customer_data_group::fetch_name',
          'set_func' => 'Config::select_customer_data_group(',
        ],
        static::CONFIG_KEY_BASE . 'REQUIRED' => [
          'title' => 'Require Email Address module (if enabled)',
          'value' => 'True',
          'desc' => 'Do you want the email address to be required in customer registration?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'ENTRY_EMAIL_ADDRESS_MIN_LENGTH' => [
          'title' => 'Minimum Length',
          'value' => '6',
          'desc' => 'Minimum length of email address',
        ],
        static::CONFIG_KEY_BASE . 'PAGES' => [
          'title' => 'Pages',
          'value' => 'account_edit;create_account;customers',
          'desc' => 'On what pages should this appear?',
          'set_func' => 'Customers::select_pages(',
          'use_func' => 'abstract_module::list_exploded',
        ],
        static::CONFIG_KEY_BASE . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '2100',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
        static::CONFIG_KEY_BASE . 'TEMPLATE' => [
          'title' => 'Template',
          'value' => 'includes/modules/customer_data/cd_whole_row_input.php',
          'desc' => 'What template should be used to surround this input?',
        ],
      ];
    }

    public function get($field, &$customer_details) {
      switch ($field) {
        case 'email_address':
          if (!isset($customer_details[$field])) {
            $customer_details[$field] = $customer_details['email_address']
              ?? $customer_details['customers_email_address'] ?? null;
          }
          return $customer_details[$field];
      }
    }

    public function display_input($customer_details = null) {
      $label_text = ENTRY_EMAIL_ADDRESS;
      $input_id = 'inputEmail';

      $input = new Input('email_address', [
        'id' => $input_id,
        'autocomplete' => 'username email',
        'placeholder' => ENTRY_EMAIL_ADDRESS_TEXT,
        'minlength' => ENTRY_EMAIL_ADDRESS_MIN_LENGTH,
      ], 'email');

      if (isset($customer_details) && is_array($customer_details)) {
        $input->set('value', $this->get('email_address', $customer_details));
      }

      if ($this->is_required()) {
        $input->require();
        $input .= FORM_REQUIRED_INPUT;
      }

      include Guarantor::ensure_global('Template')->map($this->base_constant('TEMPLATE'));
    }

    public function process(&$customer_details) {
      $customer_details['email_address'] = Text::input($_POST['email_address']);

      if (($this->is_required() || !empty($customer_details['email_address']))
        && (strlen($customer_details['email_address']) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH)
        )
      {
        $GLOBALS['messageStack']->add_classed(
          $GLOBALS['message_stack_area'] ?? 'customer_data',
          sprintf(ENTRY_EMAIL_ADDRESS_ERROR, ENTRY_EMAIL_ADDRESS_MIN_LENGTH));

        return false;
      } elseif (!self::validate($customer_details['email_address'])) {
        $GLOBALS['messageStack']->add_classed($GLOBALS['message_stack_area'] ?? 'customer_data', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);

        return false;
      } else {
        $check_email_sql = "SELECT COUNT(*) AS total FROM customers WHERE customers_email_address = '" . $GLOBALS['db']->escape($customer_details['email_address']) . "'";
        if (isset($_SESSION['customer_id']) || isset($customer_details['id'])) {
          $check_email_sql .= " AND customers_id != " . (int)($_SESSION['customer_id'] ?? $customer_details['id']);
        }

        $check_email = $GLOBALS['db']->query($check_email_sql)->fetch_assoc();
        if ($check_email['total'] > 0) {
          $GLOBALS['messageStack']->add_classed($GLOBALS['message_stack_area'] ?? 'customer_data', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);

          return false;
        }
      }

      return true;
    }

    public function build_db_values(&$db_tables, $customer_details, $table = 'both') {
      Guarantor::guarantee_subarray($db_tables, 'customers');
      $db_tables['customers']['customers_email_address'] = $customer_details['email_address'];
    }

    public function build_db_aliases(&$db_tables, $table = 'both') {
      Guarantor::guarantee_subarray($db_tables, 'customers');
      $db_tables['customers']['customers_email_address'] = 'email_address';
    }

    public static function validate($email) {
      $email = trim($email);

      if ( ( strlen($email) > 255 ) || ( false === filter_var($email, FILTER_VALIDATE_EMAIL) ) ) {
        return false;
      }

      if (ENTRY_EMAIL_ADDRESS_CHECK == 'true') {
        $domain = explode('@', $email);

        if ( !checkdnsrr($domain[1], "MX") && !checkdnsrr($domain[1], "A") ) {
          return false;
        }
      }

      return true;
    }

    public function is_searchable() {
      return true;
    }

  }
