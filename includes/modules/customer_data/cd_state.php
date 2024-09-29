<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cd_state extends abstract_customer_data_module {

    const CONFIG_KEY_BASE = 'MODULE_CUSTOMER_DATA_STATE_';

    const PROVIDES = [ 'state', 'entry_state', 'zone_id' ];
    const REQUIRES = [ 'country_id' ];

    protected function get_parameters() {
      return [
        static::CONFIG_KEY_BASE . 'STATUS' => [
          'title' => 'Enable State module',
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
          'title' => 'Require State module (if enabled)',
          'value' => 'False',
          'desc' => 'Do you want the state to be required in customer registration?  Note that any State set up as a Zone will always be required.',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'ENTRY_STATE_MIN_LENGTH' => [
          'title' => 'Minimum Length',
          'value' => '2',
          'desc' => 'Minimum length of state',
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
          'value' => '4600',
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
        case 'entry_state':
        case 'state':
          if (!isset($customer_details[$field])) {
            $customer_details[$field] = $customer_details['state']
              ?? $customer_details['entry_state'] ?? null;
          }

          if (!$customer_details[$field]) {
            $customer_details[$field] = Zone::fetch_name($this->get('zone_id', $customer_details), $GLOBALS['customer_data']->get('country_id', $customer_details), null);
          }

          return $customer_details[$field];
        case 'zone_id':
          if (!isset($customer_details[$field])) {
            $customer_details[$field] = $customer_details['zone_id']
              ?? $customer_details['entry_zone_id'] ?? null;
          }
          return $customer_details[$field];
      }
    }

    public function display_input(&$customer_details = null) {
      $label_text = ENTRY_STATE;
      $input_id = 'inputState';

      $parameters = [
        'id' => $input_id,
        'autocomplete' => 'address-level1',
        'placeholder' => ENTRY_STATE_TEXT ?? '',
        'minlength' => ENTRY_STATE_MIN_LENGTH,
      ];


      $state = null;
      $zones = null;
      if (isset($customer_details) && is_array($customer_details)) {
        $state = $this->get('state', $customer_details);
        $country_id = $GLOBALS['customer_data']->get('country_id', $customer_details);

        if ((int)$country_id > 0) {
          $zones = $GLOBALS['db']->fetch_all("SELECT zone_name AS id, zone_name AS text FROM zones WHERE zone_country_id = " . (int)$country_id . " ORDER BY zone_name");
        }
      }

      $post_input = null;
      if (empty($zones)) {
        $input = (new Input('state', $parameters));
        
        if (isset($customer_details) && is_array($customer_details)) {
          $input->set('value', $this->get('state', $customer_details));
        }
        
        if ($this->is_required()) {
          $input->require();
          $input .= FORM_REQUIRED_INPUT;
        }
      } else {
        array_unshift($zones, ['id' => '', 'text' => ENTRY_STATE_SELECT_ONE]);

        if (!Text::is_empty(ENTRY_STATE_TEXT)) {
          $parameters['aria-describedby'] = 'atState';
          $post_input .= '<span id="atState" class="form-text">' . ENTRY_STATE_TEXT . '</span>';
        }

        $input = new Select('state', $zones, $parameters);
        $input->set_selection($state);
        $input->require();
        if (isset($post_input)) {
          $input .= $post_input;
        }
        $input .= FORM_REQUIRED_INPUT;
      }

      include Guarantor::ensure_global('Template')->map($this->base_constant('TEMPLATE'));
    }

    public function fetch_zone_count($country_id) {
      static $check;

      if (!isset($check)) {
        $check = $GLOBALS['db']->query("SELECT COUNT(*) AS total FROM zones WHERE zone_country_id = " . (int)$country_id)->fetch_assoc();
      }

      return $check['total'];
    }

    public function process(&$customer_details) {
      $customer_details['state'] = Text::input($_POST['state']);
      if (isset($_POST['zone_id'])) {
        $customer_details['zone_id'] = Text::input($_POST['zone_id']);
      } else {
        $customer_details['zone_id'] = false;
      }

      $customer_details['entry_state'] = $customer_details['state'];

      $country_id = $GLOBALS['customer_data']->get('country_id', $customer_details);
      if ((int)$country_id > 0 && $this->fetch_zone_count($country_id) > 0) {
        $zone_query = $GLOBALS['db']->query("SELECT DISTINCT zone_id FROM zones WHERE zone_country_id = " . (int)$country_id
          . " AND (zone_name = '" . $GLOBALS['db']->escape($customer_details['state']) . "' OR zone_code = '" . $GLOBALS['db']->escape($customer_details['state']) . "')");
        if (mysqli_num_rows($zone_query) === 1) {
          $zone = $zone_query->fetch_assoc();
          $customer_details['zone_id'] = (int)$zone['zone_id'];
          $customer_details['entry_state'] = '';
        } else {
          $GLOBALS['messageStack']->add_classed($GLOBALS['message_stack_area'] ?? 'customer_data', ENTRY_STATE_ERROR_SELECT);

          return false;
        }
      } elseif ($this->is_required() && (strlen($customer_details['state']) < ENTRY_STATE_MIN_LENGTH)) {
        $GLOBALS['messageStack']->add_classed(
          $GLOBALS['message_stack_area'] ?? 'customer_data',
          sprintf(ENTRY_STATE_ERROR, ENTRY_STATE_MIN_LENGTH));

        return false;
      }

      return true;
    }

    public function build_db_values(&$db_tables, $customer_details, $table = 'both') {
      Guarantor::guarantee_subarray($db_tables, 'address_book');
      $db_tables['address_book']['entry_state'] = $customer_details['entry_state'];
      $db_tables['address_book']['entry_zone_id'] = $customer_details['zone_id'];
    }

    public function build_db_aliases(&$db_tables, $table = 'both') {
      Guarantor::guarantee_subarray($db_tables, 'address_book');
      $db_tables['address_book']['entry_state'] = 'state';
      $db_tables['address_book']['entry_zone_id'] = 'zone_id';
    }

  }
