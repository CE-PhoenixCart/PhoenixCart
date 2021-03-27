<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  class securityCheckExtended_mysql_utf8 {

    public $type = 'warning';
    public $has_doc = true;

    function __construct() {
      include DIR_FS_ADMIN . "includes/languages/{$_SESSION['language']}/modules/security_check/extended/mysql_utf8.php";

      $this->title = MODULE_SECURITY_CHECK_EXTENDED_MYSQL_UTF8_TITLE;
    }

    function pass() {
      $check_query = $GLOBALS['db']->query('SHOW TABLE STATUS');

      while ( $check = $check_query->fetch_assoc() ) {
        if ( isset($check['Collation']) && ($check['Collation'] !== 'utf8mb4_unicode_ci') ) {
          return false;
        }
      }

      return true;
    }

    function getMessage() {
      return '<a href="' . Guarantor::ensure_global('Admin')->link('database_tables.php') . '">' . MODULE_SECURITY_CHECK_EXTENDED_MYSQL_UTF8_ERROR . '</a>';
    }

  }
