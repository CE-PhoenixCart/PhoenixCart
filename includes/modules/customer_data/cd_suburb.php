<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cd_suburb extends abstract_customer_data_module {

    const CONFIG_KEY_BASE = 'MODULE_CUSTOMER_DATA_SUBURB_';

    const PROVIDES = [ 'suburb' ];
    const REQUIRES = [  ];

    protected function get_parameters() {
      return [
        static::CONFIG_KEY_BASE . 'STATUS' => [
          'title' => 'Enable Suburb module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        static::CONFIG_KEY_BASE . 'GROUP' => [
          'title' => 'Customer data group',
          'value' => '2',
          'desc' => 'In what group should this appear?',
          'use_func' => 'customer_data_group::fetch_name',
          'set_func' => 'Config::select_customer_data_group(',
        ],
        static::CONFIG_KEY_BASE . 'REQUIRED' => [
          'title' => 'Require Suburb module (if enabled)',
          'value' => 'True',
          'desc' => 'Do you want the suburb to be required in customer registration?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        static::CONFIG_KEY_BASE . 'MIN_LENGTH' => [
          'title' => 'Minimum Length',
          'value' => '3',
          'desc' => 'Minimum length of suburb',
        ],
        static::CONFIG_KEY_BASE . 'AUTOCOMPLETE' => [
          'title' => 'Autocomplete',
          'value' => 'address-line2',
          'desc' => 'How do you want the suburb to be autocompleted?',
          'set_func' => "Config::select_one(['address-level3', 'address-line2', 'off'], ",
        ],
        static::CONFIG_KEY_BASE . 'PAGES' => [
          'title' => 'Pages',
          'value' => 'address_book;checkout_new_address;create_account;customers',
          'desc' => 'On what pages should this appear?',
          'set_func' => 'Customers::select_pages(',
          'use_func' => 'abstract_module::list_exploded',
        ],
        static::CONFIG_KEY_BASE . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '4400',
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
        case 'suburb':
          if (!isset($customer_details[$field])) {
            $customer_details[$field] = $customer_details['suburb']
              ?? $customer_details['entry_suburb'] ?? null;
          }
          return $customer_details[$field];
      }
    }

    public function display_input($customer_details = null) {
      $label_text = ENTRY_SUBURB;
      $input_id = 'inputSuburb';

      $input = new Input('suburb', [
        'id' => $input_id,
        'autocomplete' => $this->base_constant('AUTOCOMPLETE'),
        'placeholder' => ENTRY_SUBURB_TEXT,
        'minlength' => $this->base_constant('MIN_LENGTH'),
      ]);

      if ($customer_details && is_array($customer_details)) {
        $input->set('value', $this->get('suburb', $customer_details));
      }

      if ($this->is_required()) {
        $input->require();
        $input .= FORM_REQUIRED_INPUT;
      }

      include Guarantor::ensure_global('Template')->map($this->base_constant('TEMPLATE'));
    }

    public function process(&$customer_details) {
      $customer_details['suburb'] = Text::input($_POST['suburb']);

      if (strlen($customer_details['suburb']) < $this->base_constant('MIN_LENGTH')
        && ($this->is_required()
          || !empty($customer_details['suburb'])
          )
        )
      {
        $GLOBALS['messageStack']->add_classed(
          $GLOBALS['message_stack_area'] ?? 'customer_data',
          sprintf(ENTRY_SUBURB_ERROR, $this->base_constant('MIN_LENGTH')));

        return false;
      }

      return true;
    }

    public function build_db_values(&$db_tables, $customer_details, $table = 'both') {
      Guarantor::guarantee_subarray($db_tables, 'address_book');
      $db_tables['address_book']['entry_suburb'] = $customer_details['suburb'];
    }

    public function build_db_aliases(&$db_tables, $table = 'both') {
      Guarantor::guarantee_subarray($db_tables, 'address_book');
      $db_tables['address_book']['entry_suburb'] = 'suburb';
    }

  }
