<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class customer_data_group {

    public static function fetch_options() {
      return $GLOBALS['db']->fetch_all(sprintf(<<<'EOSQL'
SELECT customer_data_groups_id AS id, customer_data_groups_name AS text
 FROM customer_data_groups
 WHERE language_id = %d
 ORDER BY cdg_vertical_sort_order, cdg_horizontal_sort_order
EOSQL
        , (int)$_SESSION['languages_id']));
    }

    public static function fetch_name($customer_data_group_id) {
      return ('0' == $customer_data_group_id)
           ? TEXT_NONE
           : $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT customer_data_groups_name
 FROM customer_data_groups
 WHERE customer_data_groups_id = %d AND language_id = %d
EOSQL
        , (int)$customer_data_group_id, (int)$_SESSION['languages_id'])
        )->fetch_assoc()['customer_data_groups_name'];
    }

  }
