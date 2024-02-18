<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cd_company extends abstract_customer_data_module {

    const CONFIG_KEY_BASE = 'MODULE_CUSTOMER_DATA_COMPANY_';

    const PROVIDES = [ 'company' ];
    const REQUIRES = [  ];

    protected function get_parameters() {
      return [
        static::CONFIG_KEY_BASE . 'STATUS' => [
          'title' => 'Enable Company module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        static::CONFIG_KEY_BASE . 'GROUP' => [
          'title' => 'Customer data group',
          'value' => '4',
          'desc' => 'In what group should this appear?',
          'use_func' => 'customer_data_group::fetch_name',
          'set_func' => 'Config::select_customer_data_group(',
        ],
        static::CONFIG_KEY_BASE . 'REQUIRED' => [
          'title' => 'Require Company module (if enabled)',
          'value' => 'False',
          'desc' => 'Do you want the company to be required in customer registration?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        static::CONFIG_KEY_BASE . 'MIN_LENGTH' => [
          'title' => 'Minimum Length',
          'value' => '3',
          'desc' => 'Minimum length of company',
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
          'value' => '3500',
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
        case 'company':
          if (!isset($customer_details[$field])) {
            $customer_details[$field] = $customer_details['company']
              ?? $customer_details['entry_company'] ?? null;
          }
          return $customer_details[$field];
      }
    }

    public function display_input($customer_details = null) {
      $label_text = ENTRY_COMPANY;
      $input_id = 'inputCompany';

      $parameters = [
        'id' => $input_id,
        'autocomplete' => 'organization',
        'placeholder' => ENTRY_COMPANY_TEXT,
        'minlength' => $this->base_constant('MIN_LENGTH'),
      ];

      $input = new Input('company', $parameters);

      if (isset($customer_details) && is_array($customer_details)) {
        $input->set('value', $this->get('company', $customer_details));
      }

      if ($this->is_required()) {
        $input->require();
        $input .= FORM_REQUIRED_INPUT;
      }

      include Guarantor::ensure_global('Template')->map($this->base_constant('TEMPLATE'));
    }

    public function process(&$customer_details) {
      $customer_details['company'] = Text::input($_POST['company']);

      if (strlen($customer_details['company']) < $this->base_constant('MIN_LENGTH')
        && ($this->is_required() || $customer_details['company'])
        )
      {
        $GLOBALS['messageStack']->add_classed(
          $GLOBALS['message_stack_area'] ?? 'customer_data',
          sprintf(ENTRY_COMPANY_ERROR, $this->base_constant('MIN_LENGTH')));

        return false;
      }

      return true;
    }

    public function build_db_values(&$db_tables, $customer_details, $table = 'both') {
      Guarantor::guarantee_subarray($db_tables, 'address_book');
      $db_tables['address_book']['entry_company'] = $customer_details['company'];
    }

    public function build_db_aliases(&$db_tables, $table = 'both') {
      Guarantor::guarantee_subarray($db_tables, 'address_book');
      $db_tables['address_book']['entry_company'] = 'company';
    }

  }
