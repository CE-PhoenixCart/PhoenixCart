<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $link->set_parameter('zID', (int)$_GET['zID'])->set_parameter('action', 'list');
  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_COUNTRY,
        'function' => function ($row) {
          return $row['countries_name'] ?? TEXT_ALL_COUNTRIES;
        },
      ],
      [
        'name' => TABLE_HEADING_COUNTRY_ZONE,
        'function' => function ($row) {
          return $row['zone_name'] ?? PLEASE_SELECT;
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->association_id) && ($row['info']->association_id === $row['association_id']))
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_COUNTRIES,
    'page' => $_GET['spage'] ?? null,
    'page_name' => 'spage',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => sprintf(<<<'EOSQL'
SELECT a.*, c.countries_name, z.zone_name
 FROM zones_to_geo_zones a
   LEFT JOIN countries c ON a.zone_country_id = c.countries_id
   LEFT JOIN zones z ON a.zone_id = z.zone_id
 WHERE a.geo_zone_id = %d
 ORDER BY association_id
EOSQL
      , (int)$_GET['zID']),
  ];

  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['function'] = function (&$row) use (&$table_definition, $link) {
    $row['onclick'] = $link->set_parameter('sID', $row['association_id']);
    if (!isset($table_definition['info']) && (!isset($_GET['sID']) || ($_GET['sID'] === $row['association_id']))) {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['onclick'] = (clone $row['onclick'])->set_parameter('saction', 'edit');
      $row['css'] = ' class="table-active"';
    } else {
      $row['css'] = '';
    }
  };

  $admin_hooks->set('buttons', 'new_geo_zone', function () use ($link) {
    if (empty($GLOBALS['saction'])) {
      $insert_link = (clone $link)->set_parameter('saction', 'new')->delete_parameter('sID');
      $button = $GLOBALS['Admin']->button(IMAGE_INSERT, 'fas fa-plus', 'btn-warning', $insert_link);

      return <<<"EOHTML"
          <div class="row">
            <div class="col"><p class="pt-2 text-end">{$button}</p></div>
          </div>
EOHTML;
    }
  });

  $table_definition['split']->display_table();
?>
