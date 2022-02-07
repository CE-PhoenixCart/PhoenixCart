<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
  if (!Session::is_started()) {
    if ( !isset($_GET['cookie_test']) ) {
      Href::redirect($Linker->build('login.php')->retain_query_except()->set_parameter('cookie_test', '1'));
    }

    Href::redirect($Linker->build('cookie_usage.php'));
  }

  // login content module must return $login_customer_id as an integer after successful customer authentication	
  $login_customer_id = false;
  $page_content = $Template->get_content('login');

  if ( is_int($login_customer_id) && ($login_customer_id > 0) ) {
    $hooks->cat('postLogin');

    Href::redirect($_SESSION['navigation']->pop_snapshot_as_link());
  }

  require language::map_to_translation('login.php');
  require $Template->map(__FILE__, 'page');
  require 'includes/application_bottom.php';
