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
        'class' => 'text-right',
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

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col text-right align-self-center">
      <?=
        empty($action)
      ? $Admin->button(IMAGE_NEW_TAX_RATE, 'fas fa-percent', 'btn-danger', (clone $link)->set_parameter('action', 'new'))
      : $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light mt-2', $link)
      ?>
    </div>
  </div>

<?php
  $table_definition['split']->display_table();

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
