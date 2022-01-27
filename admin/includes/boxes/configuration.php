<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $cl_box_groups[] = ['heading' => BOX_HEADING_CONFIGURATION, 'apps' => []];

  $configuration_groups_query = $GLOBALS['db']->query("SELECT configuration_group_id AS cgID, configuration_group_title AS cgTitle FROM configuration_group WHERE visible = '1' ORDER BY sort_order");
  while ($configuration_groups = $configuration_groups_query->fetch_assoc()) {
    $cl_box_groups[count($cl_box_groups)-1]['apps'][] = [
      'code' => 'configuration.php',
      'title' => $configuration_groups['cgTitle'],
      'link' => $GLOBALS['Admin']->link('configuration.php', ['gID' => $configuration_groups['cgID']]),
    ];
  }
