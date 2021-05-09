<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class order_status {

    public static function fetch_name($orders_status_id, $language_id = '') {
      if ($order_status_id < 1) {
        return TEXT_DEFAULT;
      }

      if (!$language_id) {
        $language_id = $_SESSION['languages_id'];
      }

      $orders_status_query = $GLOBALS['db']->query("SELECT orders_status_name FROM orders_status WHERE orders_status_id = " . (int)$orders_status_id . " AND language_id = " . (int)$language_id);
      $orders_status = $orders_status_query->fetch_assoc();

      return $orders_status['orders_status_name'];
    }

    public static function fetch_options() {
      return $GLOBALS['db']->fetch_all("SELECT orders_status_id AS id, orders_status_name AS text FROM orders_status WHERE language_id = " . (int)$_SESSION['languages_id'] . " ORDER BY orders_status_id");
    }

  }
