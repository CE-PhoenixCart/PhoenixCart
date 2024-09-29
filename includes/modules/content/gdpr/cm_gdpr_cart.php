<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_gdpr_cart extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_GDPR_CART_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      global $port_my_data;

      if ($_SESSION['cart']->count_contents() > 0) {
        $port_my_data['YOU']['CART']['COUNT'] = $_SESSION['cart']->count_contents();

        $n = 1;
        foreach ($_SESSION['cart']->get_products() as $p) {
          $port_my_data['YOU']['CART']['LIST'][$n]['ID'] = (int)$p->get('id');
          $port_my_data['YOU']['CART']['LIST'][$n]['QTY'] = (int)$p->get('quantity');
          $port_my_data['YOU']['CART']['LIST'][$n]['NAME'] = $p->get('name');

          $n++;
        }

        $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
        include 'includes/modules/content/cm_template.php';
      }
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Cart Contents Module',
          'value' => 'True',
          'desc' => 'Should this module be shown on the GDPR page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '800',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
