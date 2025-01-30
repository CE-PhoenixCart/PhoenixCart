<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  foreach ( $cl_box_groups as &$group ) {
    if ( $group['heading'] == BOX_HEADING_CATALOG ) {
      $group['apps'][] = [
        'code' => 'importers.php',
        'title' => MODULES_ADMIN_MENU_CATALOG_IMPORTERS,
        'link' => $GLOBALS['Admin']->link('importers.php'),
      ];

      break;
    }
  }

