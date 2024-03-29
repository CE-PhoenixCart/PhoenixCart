<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cd_lastname extends abstract_customer_data_module {

    const CONFIG_KEY_BASE = 'MODULE_CUSTOMER_DATA_LASTNAME_';

    const PROVIDES = [ 'lastname' ];
    const REQUIRES = [  ];

    protected function get_parameters() {
      return [
        static::CONFIG_KEY_BASE . 'STATUS' => [
          'title' => 'Enable Last Name module',
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
          'title' => 'Require Last Name module (if enabled)',
          'value' => 'True',
          'desc' => 'Do you want the last name to be required in customer registration?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'ENTRY_LAST_NAME_MIN_LENGTH' => [
          'title' => 'Minimum Length',
          'value' => '2',
          'desc' => 'Minimum length of last name',
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
          'value' => '2070',
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
        case 'lastname':
          $customer_details[$field] = $customer_details['lastname']
            ?? $customer_details['customers_lastname']
            ?? $customer_details['entry_lastname'] ?? null;
          return $customer_details[$field];
      }
    }

    public function display_input($customer_details = null) {
      $label_text = ENTRY_LAST_NAME;

      $input_id = 'inputLastName';
      $parameters = [
        'id' => $input_id,
        'autocomplete' => 'family-name',
        'placeholder' => ENTRY_LAST_NAME_TEXT,
        'minlength' => ENTRY_LAST_NAME_MIN_LENGTH,
      ];

      $input = new Input('lastname', $parameters);

      if (isset($customer_details) && is_array($customer_details)) {
        $input->set('value', $this->get('lastname', $customer_details));
      }

      if ($this->is_required()) {
        $input->require();
        $input .= FORM_REQUIRED_INPUT;
      }

      include Guarantor::ensure_global('Template')->map($this->base_constant('TEMPLATE'));
    }

    public function process(&$customer_details) {
      $customer_details['lastname'] = Text::input($_POST['lastname']);

      if (($this->is_required() || !empty($customer_details['lastname']))
        && (strlen($customer_details['lastname']) < ENTRY_LAST_NAME_MIN_LENGTH)
        )
      {
        $GLOBALS['messageStack']->add_classed(
          $GLOBALS['message_stack_area'] ?? 'customer_data',
          sprintf(ENTRY_LAST_NAME_ERROR, ENTRY_LAST_NAME_MIN_LENGTH));

        return false;
      }

      return true;
    }

    public function build_db_values(&$db_tables, $customer_details, $table = 'both') {
      if ('both' == $table || 'customers' == $table) {
        Guarantor::guarantee_subarray($db_tables, 'customers');
        $db_tables['customers']['customers_lastname'] = $customer_details['lastname'];
      }

      if ('both' == $table || 'address_book' == $table) {
        Guarantor::guarantee_subarray($db_tables, 'address_book');
        $db_tables['address_book']['entry_lastname'] = $customer_details['lastname'];
      }
    }

    public function build_db_aliases(&$db_tables, $table = 'both') {
      if ('both' == $table || 'customers' == $table) {
        Guarantor::guarantee_subarray($db_tables, 'customers');
        $db_tables['customers']['customers_lastname'] = 'lastname';
      }

      if ('both' == $table || 'address_book' == $table) {
        Guarantor::guarantee_subarray($db_tables, 'address_book');
        $db_tables['address_book']['entry_lastname'] = (isset($db_tables['customers']['customers_lastname'])) ? null : 'lastname';
      }
    }

    public function is_searchable() {
      return true;
    }

  }
