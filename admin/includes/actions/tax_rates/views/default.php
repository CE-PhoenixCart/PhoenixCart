<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_TAX_RATE_PRIORITY,
        'function' => function ($row) {
          return $row['tax_priority'];
        },
      ],
      [
        'name' => TABLE_HEADING_TAX_CLASS_TITLE,
        'function' => function ($row) {
          return $row['tax_class_title'];
        },
      ],
      [
        'name' => TABLE_HEADING_ZONE,
        'function' => function ($row) {
          return $row['geo_zone_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_TAX_RATE,
        'function' => function ($row) {
          return Tax::format($row['tax_rate']);
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->tax_rates_id) && ($row['tax_rates_id'] == $row['info']->tax_rates_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $GLOBALS['link'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_TAX_RATES,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'tID',
    'db_id' => 'tax_rates_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT r.*, z.*, tc.* FROM tax_class tc, tax_rates r LEFT JOIN geo_zones z ON r.tax_zone_id = z.geo_zone_id WHERE r.tax_class_id = tc.tax_class_id",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $GLOBALS['link']->set_parameter('tID', $row['tax_rates_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['tID']) || ($_GET['tID'] == $row['tax_rates_id']))
      && !Text::is_prefixed_by($GLOBALS['action'], 'new'))
    {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['css'] = ' class="table-active"';
      $row['onclick'] = (clone $GLOBALS['link'])->set_parameter('action', 'edit');
    } else {
      $row['css'] = '';
      $row['onclick'] = $GLOBALS['link'];
    }
  };

  $table_definition['split'] = new Paginator($table_definition);

  $table_definition['split']->display_table();
