<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  foreach ( $cl_box_groups as &$group ) {
    if ( $group['heading'] == BOX_HEADING_LOCALIZATION ) {
      $group['apps'][] = [
        'code' => 'currencies.php',
        'title' => MODULES_ADMIN_MENU_LOCALIZATION_CURRENCIES,
        'link' => $GLOBALS['Admin']->link('currencies.php'),
      ];

      break;
    }
  }
