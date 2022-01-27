<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  foreach ( $cl_box_groups as &$group ) {
    if ( $group['heading'] == BOX_HEADING_CONFIGURATION ) {
      $group['apps'][] = [
        'code' => 'store_logo.php',
        'title' => MODULES_ADMIN_MENU_CONFIGURATION_STORE_LOGO,
        'link' => $GLOBALS['Admin']->link('store_logo.php'),
      ];

      break;
    }
  }
