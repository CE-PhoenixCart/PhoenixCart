<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $link = $Admin->link()->retain_query_except(['mID', 'action']);
  require 'includes/segments/process_action.php';

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_MANUFACTURERS,
        'is_heading' => true,
        'function' => function ($row) {
          return $row['manufacturers_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-right',
        'function' => function ($row) {
          return (isset($row['info']->manufacturers_id) && ($row['manufacturers_id'] == $row['info']->manufacturers_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'mID',
    'db_id' => 'manufacturers_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT * FROM manufacturers ORDER BY manufacturers_name",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = $GLOBALS['link']->set_parameter(
      'mID', $row['manufacturers_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['mID']) || ($_GET['mID'] == $row['manufacturers_id']))
      && !Text::is_prefixed_by($GLOBALS['action'], 'new'))
    {
      $row = array_merge($row, $GLOBALS['db']->query("SELECT COUNT(*) AS products_count FROM products WHERE manufacturers_id = " . (int)$row['manufacturers_id'])->fetch_assoc());

      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['css'] = ' class="table-active"';
      $row['onclick'] = (clone $row['onclick'])->set_parameter('action', 'edit');
    } else {
      $row['css'] = '';
    }
  };

  $table_definition['split'] = new Paginator($table_definition);

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE; ?></h1>
    </div>
    <div class="col text-right align-self-center">
      <?=
        empty($action)
      ? $Admin->button(BUTTON_INSERT_NEW_MANUFACTURER, 'fas fa-id-card', 'btn-danger', $Admin->link('manufacturers.php', ['action' => 'new']))
      : $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $link)
      ?>
    </div>
  </div>

<?php
  $table_definition['split']->display_table();
?>

  <script>
    document.querySelector('#inputManufacturersImage').addEventListener('change', function (event) {
      var labels = document.querySelectorAll('LABEL.custom-file-label');
      for (var i = 0; i < labels.length; i++) {
        if ('inputManufacturersImage' === labels[i].htmlFor) {
          labels[i].innerHTML = event.target.files[0].name;
        }
      }
    });
  </script>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
