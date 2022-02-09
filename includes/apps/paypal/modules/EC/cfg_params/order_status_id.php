<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class OSCOM_PayPal_EC_Cfg_order_status_id {
    var $default = '0';
    var $title;
    var $description;
    var $sort_order = 800;

    function __construct() {
      global $OSCOM_PayPal;

      $this->title = $OSCOM_PayPal->getDef('cfg_ec_order_status_id_title');
      $this->description = $OSCOM_PayPal->getDef('cfg_ec_order_status_id_desc');
    }

    function getSetField() {
      global $OSCOM_PayPal;

      $statuses_array = array(array('id' => '0', 'text' => $OSCOM_PayPal->getDef('cfg_ec_order_status_id_default')));

      $statuses_query = $GLOBALS['db']->query("select orders_status_id, orders_status_name from orders_status where language_id = '" . (int)$_SESSION['languages_id'] . "' order by orders_status_name");
      while ($statuses = $statuses_query->fetch_assoc()) {
        $statuses_array[] = array('id' => $statuses['orders_status_id'],
                                  'text' => $statuses['orders_status_name']);
      }

      $input = (new Select('order_status_id', $statuses_array, ['id' => 'inputEcOrderStatusId']))->set_selection(OSCOM_APP_PAYPAL_EC_ORDER_STATUS_ID);

      $result = <<<EOT
<h5>{$this->title}</h5>
<p>{$this->description}</p>

<div class="mb-3">{$input}</div>
EOT;

      return $result;
    }
  }
