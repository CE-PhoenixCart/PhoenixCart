<?php
/*
  $Id$

  Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/

  foreach ( $cl_box_groups as &$group ) {
    if ( $group['heading'] == BOX_HEADING_CUSTOMERS ) {
      $group['apps'][] = [
        'code' => 'reviews.php',
        'title' => MODULES_ADMIN_MENU_CUSTOMERS_REVIEWS,
        'link' => $GLOBALS['Admin']->link('reviews.php'),
      ];

      break;
    }
  }
