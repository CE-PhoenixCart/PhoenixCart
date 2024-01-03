<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class sce_mysql_utf8 {

    public $title;
    public $type = 'warning';
    public $has_doc = true;

    public function __construct() {
      $this->title = MODULE_SECURITY_CHECK_EXTENDED_MYSQL_UTF8_TITLE;
    }

    public function pass() {
      $check_query = $GLOBALS['db']->query('SHOW TABLE STATUS');

      while ( $check = $check_query->fetch_assoc() ) {
        if ( isset($check['Collation']) && ($check['Collation'] !== 'utf8mb4_unicode_ci') ) {
          return false;
        }
      }

      return true;
    }

    public function get_message() {
      return '<a href="' . Guarantor::ensure_global('Admin')->link('database_tables.php') . '">' . MODULE_SECURITY_CHECK_EXTENDED_MYSQL_UTF8_ERROR . '</a>';
    }

  }
