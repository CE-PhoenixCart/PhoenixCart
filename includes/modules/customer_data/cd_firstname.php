<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cd_firstname extends abstract_customer_data_module {

    const CONFIG_KEY_BASE = 'MODULE_CUSTOMER_DATA_FIRSTNAME_';

    const PROVIDES = [ 'firstname' ];
    const REQUIRES = [  ];

    public function get_parameters() {
      return [
        static::CONFIG_KEY_BASE . 'STATUS' => [
          'title' => 'Enable First Name module',
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
          'title' => 'Require First Name',
          'value' => 'True',
          'desc' => 'Do you want the first name to be required in customer registration?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'ENTRY_FIRST_NAME_MIN_LENGTH' => [
          'title' => 'Minimum Length',
          'value' => '2',
          'desc' => 'Minimum length of first name',
        ],
        static::CONFIG_KEY_BASE . 'PAGES' => [
          'title' => 'Pages',
          'value' => 'account_edit;address_book;checkout_new_address;create_account;customers',
          'desc' => 'On what pages should this appear?',
          'set_func' => 'Customers::select_pages(',
          'use_func' => 'abstract_module::list_exploded',
        ],
        static::CONFIG_KEY_BASE . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '2030',
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
        case 'firstname':
          if (!isset($customer_details[$field])) {
            $customer_details[$field] = $customer_details['customers_firstname']
                                     ?? $customer_details['entry_firstname'] ?? null;
          }

          return $customer_details[$field];
      }
    }

    public function display_input($customer_details = null) {
      $label_text = ENTRY_FIRST_NAME;

      $input_id = 'inputFirstName';

      $input = new Input('firstname', [
        'id' => $input_id,
        'autocomplete' => 'given-name',
        'placeholder' => ENTRY_FIRST_NAME_TEXT,
        'minlength' => ENTRY_FIRST_NAME_MIN_LENGTH,
      ]);

      if (isset($customer_details) && is_array($customer_details)) {
        $input->set('value', $this->get('firstname', $customer_details));
      }

      if ($this->is_required()) {
        $input->require();
        $input .= FORM_REQUIRED_INPUT;
      }

      include Guarantor::ensure_global('Template')->map($this->base_constant('TEMPLATE'));
    }

    public function process(&$customer_details) {
      $customer_details['firstname'] = Text::input($_POST['firstname']);

      if ($this->is_required() && (strlen($customer_details['firstname']) < ENTRY_FIRST_NAME_MIN_LENGTH)) {
        $GLOBALS['messageStack']->add_classed(
          $GLOBALS['message_stack_area'] ?? 'customer_data',
          sprintf(ENTRY_FIRST_NAME_ERROR, ENTRY_FIRST_NAME_MIN_LENGTH));

        return false;
      }

      return true;
    }

    public function build_db_values(&$db_tables, $customer_details, $table = 'both') {
      if (('both' === $table) || ('customers' === $table)) {
        Guarantor::guarantee_subarray($db_tables, 'customers');
        $db_tables['customers']['customers_firstname'] = $customer_details['firstname'] ?? null;
      }

      if (('both' === $table) || ('address_book' === $table)) {
        Guarantor::guarantee_subarray($db_tables, 'address_book');
        $db_tables['address_book']['entry_firstname'] = $customer_details['entry_firstname'] ?? $customer_details['firstname'] ?? null;
      }
    }

    public function build_db_aliases(&$db_tables, $table = 'both') {
      if ('both' == $table || 'customers' == $table) {
        Guarantor::guarantee_subarray($db_tables, 'customers');
        $db_tables['customers']['customers_firstname'] = 'firstname';
      }

      if ('both' == $table || 'address_book' == $table) {
        Guarantor::guarantee_subarray($db_tables, 'address_book');
        $db_tables['address_book']['entry_firstname'] = isset($db_tables['customers']['customers_firstname']) ? null : 'firstname';
      }
    }

    public function is_searchable() {
      return true;
    }

  }
