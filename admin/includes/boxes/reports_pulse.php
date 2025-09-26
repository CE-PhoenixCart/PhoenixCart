<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  foreach ( $cl_box_groups as &$group ) {
    if ( $group['heading'] == BOX_HEADING_REPORTS ) {
      $group['apps'][] = [
        'code' => 'pulse_analytics.php',
        'title' => MODULES_ADMIN_MENU_REPORTS_PULSE,
        'link' => $GLOBALS['Admin']->link('pulse_analytics.php'),
      ];

      break;
    }
  }
