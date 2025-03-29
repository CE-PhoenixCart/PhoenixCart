<?php
/*
* $Id: deleteStripeOrders.php
* $Loc: /includes/hooks/admin/orders/
*
* Name: StripeSCA
* Version: 1.70
* Release Date: 2025-03-02
* Author: Rainer Schmied
* 	 phoenixcartaddonsaddons.com / raiwa@phoenixcartaddons.com
*
* License: Released under the GNU General Public License
*
* Comments: Author: [Rainer Schmied @raiwa]
* Author URI: [www.phoenixcartaddons.com]
* 
* CE Phoenix, E-Commerce made Easy
* https://phoenixcart.org
* 
* Copyright (c) 2021 Phoenix Cart
* 
* 
*/

class hook_admin_orders_deleteStripeOrders {

  function listen_injectSiteStart() {
    global $db;

    if ( defined('MODULE_PAYMENT_STRIPE_SCA_STATUS') 
         && MODULE_PAYMENT_STRIPE_SCA_STATUS == 'True' 
         && !empty(MODULE_PAYMENT_STRIPE_SCA_DAYS_DELETE) 
         && !isset($_SESSION['stripe_orders_deleted']) ) {
           
      $this->load_lang();

      $delete_stripe_query = $db->query(sprintf(<<<'EOSQL'
SELECT GROUP_CONCAT(orders_id)
  FROM orders
  WHERE orders_status = %s
    AND payment_method like '%%%s%%'
    AND date_purchased <= curdate() - interval %s day
EOSQL
        , (int)MODULE_PAYMENT_STRIPE_SCA_PREPARE_ORDER_STATUS_ID, MODULE_PAYMENT_STRIPE_SCA_TEXT_PUBLIC_TITLE, MODULE_PAYMENT_STRIPE_SCA_DAYS_DELETE));

      $stripe_orders = $delete_stripe_query->fetch_assoc();
      $stripe_orders_to_delete = $stripe_orders['GROUP_CONCAT(orders_id)'];

      if ( !empty($stripe_orders_to_delete) ) {

        $db->query(sprintf(<<<'EOSQL'
DELETE
  FROM orders
  WHERE orders_id IN ('%s')
EOSQL
        , $stripe_orders_to_delete));

        $db->query(sprintf(<<<'EOSQL'
DELETE
  FROM orders_products
  WHERE orders_id IN ('%s')
EOSQL
        , $stripe_orders_to_delete));

        $db->query(sprintf(<<<'EOSQL'
DELETE
  FROM orders_products_attributes
  WHERE orders_id IN ('%s')
EOSQL
        , $stripe_orders_to_delete));

        $db->query(sprintf(<<<'EOSQL'
DELETE
  FROM orders_products_download
  WHERE orders_id IN ('%s')
EOSQL
        , $stripe_orders_to_delete));

        $db->query(sprintf(<<<'EOSQL'
DELETE
  FROM orders_status_history
  WHERE orders_id IN ('%s')
EOSQL
        , $stripe_orders_to_delete));

        $db->query(sprintf(<<<'EOSQL'
DELETE
  FROM orders_total
  WHERE orders_id IN ('%s')
EOSQL
        , $stripe_orders_to_delete));
      }

      $_SESSION['stripe_orders_deleted'] = true;

    }
  }

  function load_lang() {
    require language::map_to_translation('modules/payment/stripe_sca.php');
  }

}
