<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $page_contents = (isset($_GET['step']) && is_numeric($_GET['step']))
                 ? "install_{$_GET['step']}.php"
                 : basename(__FILE__);

  if (!file_exists("templates/pages/$page_contents")) {
    exit();
  }

  require 'includes/application.php';
  require 'templates/main_page.php';
?>
