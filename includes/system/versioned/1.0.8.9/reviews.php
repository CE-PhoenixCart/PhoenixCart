<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class Reviews {

    public static function verify_not_reviewed() {
      $reviewed_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT products_id FROM reviews WHERE customers_id = %d AND products_id = %d LIMIT 1
EOSQL
        , (int)$_SESSION['customer_id'], (int)$GLOBALS['product']->get('id')));

      if (mysqli_num_rows($reviewed_query) >= 1) {
        $GLOBALS['messageStack']->add_session('product_action', sprintf(TEXT_ALREADY_REVIEWED, $GLOBALS['customer']->get('short_name')), 'error');

        Href::redirect($GLOBALS['Linker']->build('product_info.php')->retain_query_except(['action']));
      }
    }

    public static function verify_buyer() {
      if ('true' === ALLOW_ALL_REVIEWS) {
        return;
      }

      $reviewable_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT op.products_id
 FROM orders_products op
  INNER JOIN orders o ON o.orders_id = op.orders_id
  LEFT JOIN reviews r ON o.customers_id = r.customers_id AND op.products_id = r.products_id
 WHERE o.customers_id = %d AND op.products_id = %d AND r.products_id IS NULL
 LIMIT 1
EOSQL
        , (int)$_SESSION['customer_id'], (int)$GLOBALS['product']->get('id')));

      if (!mysqli_num_rows($reviewable_query)) {
        $GLOBALS['messageStack']->add_session(
          'product_action',
          sprintf(TEXT_NOT_PURCHASED, $GLOBALS['customer']->get('short_name')),
          'error');

        Href::redirect($GLOBALS['Linker']->build('product_info.php')->retain_query_except(['action']));
      }
    }

  }
