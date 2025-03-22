<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  foreach ( $cl_box_groups as &$group ) {
    if ( $group['heading'] == BOX_HEADING_TOOLS ) {
      $group['apps'][] = [
        'code' => 'language_explorer.php',
        'title' => MODULES_ADMIN_MENU_TOOLS_LANGUAGE_EXPLORER,
        'link' => $GLOBALS['Admin']->link('language_explorer.php'),
      ];

      break;
    }
  }
