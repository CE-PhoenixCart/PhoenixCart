<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cd_dob extends abstract_customer_data_module {

    const CONFIG_KEY_BASE = 'MODULE_CUSTOMER_DATA_DOB_';

    const PROVIDES = [ 'dob' ];
    const REQUIRES = [  ];

    protected function get_parameters() {
      return [
        static::CONFIG_KEY_BASE . 'STATUS' => [
          'title' => 'Enable Date of Birth module',
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
          'title' => 'Require Date of Birth module (if enabled)',
          'value' => 'True',
          'desc' => 'Do you want the date of birth to be required in customer registration?',
          'set_func' => "Config::select_one(['True', 'False'], ",
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
          'value' => '2200',
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
        case 'dob':
          $customer_details[$field] = $customer_details['dob']
            ?? $customer_details['customers_dob'] ?? null;
          return $customer_details[$field];
      }
    }

    public function display_input($customer_details = null) {
      $label_text = ENTRY_DOB;
      $input_id = 'dob';

      $input = new Input('dob', [
        'id' => $input_id,
        'onfocus' => 'this.showPicker?.()',
        'autocomplete' => 'bday',
        'max' => date('Y-m-d'),
      ], 'date');

      if (isset($customer_details) && is_array($customer_details)) {
        $input->set('value', $this->get('dob', $customer_details));
      }

      if ($this->is_required()) {
        $input->require();
        $input .= FORM_REQUIRED_INPUT;
      }

      include Guarantor::ensure_global('Template')->map($this->base_constant('TEMPLATE'));
    }

    public function normalize_date(&$date) {
      if (empty($date)) {
        return $this->is_required();
      }
      
      return true;
    }

    public function process(&$customer_details) {
      $customer_details['dob'] = Text::input($_POST['dob']);

      if ($this->normalize_date($customer_details['dob'])) {
        return true;
      }

      $GLOBALS['messageStack']->add_classed(
        $GLOBALS['message_stack_area'] ?? 'customer_data',
        sprintf(ENTRY_DOB_ERROR));

      error_log(sprintf('Date input as [%s] and parsed as [%s]', $_POST['dob'] ?? '', $customer_details['dob']));
      $customer_details['dob'] = null;

      return false;
    }

    public function build_db_values(&$db_tables, $customer_details, $table = 'both') {
      Guarantor::guarantee_subarray($db_tables, 'customers');
      $db_tables['customers']['customers_dob'] = $customer_details['dob'];
    }

    public function build_db_aliases(&$db_tables, $table = 'both') {
      Guarantor::guarantee_subarray($db_tables, 'customers');
      $db_tables['customers']['customers_dob'] = 'dob';
    }

  }
