<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_TAX_ZONES,
        'function' => function ($row) {
          return '<a href="' . $row['onclick'] . '"><i class="fas fa-folder text-warning"></i></a>&nbsp;' . $row['geo_zone_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->geo_zone_id) && ($row['info']->geo_zone_id === $row['geo_zone_id']))
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['link'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_TAX_ZONES,
    'page' => $_GET['page'] ?? null,
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT * FROM geo_zones ORDER BY geo_zone_name",
  ];

  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['link'] = (clone $GLOBALS['link'])->set_parameter('zID', $row['geo_zone_id']);
    if (!isset($table_definition['info']) && (!isset($_GET['zID']) || ($_GET['zID'] === $row['geo_zone_id'])) && !Text::is_prefixed_by($GLOBALS['action'],  'new')) {
      $num_zones = $GLOBALS['db']->query("SELECT COUNT(*) AS num_zones FROM zones_to_geo_zones WHERE geo_zone_id = " . (int)$row['geo_zone_id'] . " GROUP BY geo_zone_id")->fetch_assoc();

      $row['num_zones'] = $num_zones['num_zones'] ?? 0;
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['onclick'] = (clone $row['link'])->set_parameter('action', 'list');
      $row['css'] = ' class="table-active"';
    } else {
      $row['onclick'] = $row['link'];
      $row['css'] = '';
    }
  };

  $table_definition['split']->display_table();
?>
