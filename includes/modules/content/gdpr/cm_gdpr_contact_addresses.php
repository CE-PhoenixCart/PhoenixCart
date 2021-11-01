<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_gdpr_contact_addresses extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_GDPR_CONTACT_ADDRESSES_';

    public function __construct() {
      parent::__construct(__FILE__);
      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
    }

    public function execute() {
      global $port_my_data, $customer;

      $addresses_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT address_book_id
 FROM address_book
 WHERE customers_id = %d AND address_book_id != %d
EOSQL
        , (int)$_SESSION['customer_id'], (int)$customer->get('default_address_id')));

      $num_addresses = mysqli_num_rows($addresses_query);

      if ($num_addresses > 0) {
        $port_my_data['YOU']['CONTACT']['ADDRESS']['OTHER']['COUNT'] = $num_addresses;

        $a = 1;
        while ($address = $addresses_query->fetch_assoc()) {
          $port_my_data['YOU']['CONTACT']['ADDRESS']['OTHER']['LIST'][$a]['ID'] = (int)$address['address_book_id'];
          $port_my_data['YOU']['CONTACT']['ADDRESS']['OTHER']['LIST'][$a]['ADDRESS'] = $customer->make_address_label($address['address_book_id'], true, ' ', ', ');

          $a++;
        }

        $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
        include 'includes/modules/content/cm_template.php';
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_GDPR_CONTACT_ADDRESSES_STATUS' => [
          'title' => 'Enable Addresses Module',
          'value' => 'True',
          'desc' => 'Should this module be shown on the GDPR page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_GDPR_CONTACT_ADDRESSES_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '12',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_GDPR_CONTACT_ADDRESSES_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '150',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
