<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $link = $Admin->link();
  if (isset($_GET['page'])) {
    $link->set_parameter('page', (int)$_GET['page']);
  }

  require 'includes/segments/process_action.php';

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_COUNTRY_NAME,
        'function' => function ($row) {
          return $row['countries_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_ZONE_NAME,
        'function' => function ($row) {
          return $row['zone_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_ZONE_CODE,
        'class' => 'text-right',
        'function' => function ($row) {
          return $row['zone_code'];
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-right',
        'function' => function ($row) {
          return (isset($table_definition['info']->zone_id) && ($row['zone_id'] == $table_definition['info']->zone_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $GLOBALS['link']->set_parameter('cID', $row['zone_id']) . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_ZONES,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'cID',
    'db_id' => 'zone_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT z.*, c.countries_id, c.countries_name FROM zones z, countries c WHERE z.zone_country_id = c.countries_id ORDER BY c.countries_name, z.zone_name",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = $GLOBALS['Admin']->link();
    $row['onclick']->retain_query_except(['action'])->set_parameter(
      'cID', $row['zone_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['cID']) || ($_GET['cID'] == $row['zone_id'])))
    {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['css'] = ' class="table-active"';
    } else {
      $row['css'] = '';
    }
  };

  $table_definition['split'] = new Paginator($table_definition);

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col"><h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1></div>
    <div class="col text-right align-self-center">
      <?= empty($action)
        ? $Admin->button(IMAGE_NEW_ZONE, 'fas fa-map-marker-alt', 'btn-danger', $Admin->link('zones.php', ['action' => 'new']))
        : $Admin->button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-light mt-2', $link)
      ?>
    </div>
  </div>

<?php
  $table_definition['split']->display_table();
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
