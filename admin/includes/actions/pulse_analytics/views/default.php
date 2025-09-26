<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/
?>

<div class="row row-cols-4 mb-3">
  <?= $admin_hooks->cat('pulseLive'); ?>
</div>

<?php
  $conditions = [];

  if (!Text::is_empty($_GET['customer'] ?? '')) {
    $customer = (int)Text::input($_GET['customer']);
    $conditions[] = "customer_id = $customer";
  }

  if (!Text::is_empty($_GET['product'] ?? '')) {
    $product = (int)Text::input($_GET['product']);
    $conditions[] = "product_id = $product";
  }
  
  if (!empty($conditions)) {
    $admin_hooks->set('eventListButtons', 'reset_keywords', function () {
      return $GLOBALS['Admin']->button(IMAGE_RESET, 'fas fa-angle-left', 'btn-light', $GLOBALS['Admin']->link('pulse_analytics.php'));
    });
  }

  $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

  $analytics_sql = <<<EOSQL
SELECT *
 FROM analytics_events
 $where
 ORDER BY id DESC
EOSQL;

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_EVENT_TYPE,
        'function' => function (&$row) {
          return $row['event_type'];
        },
      ],
      [
        'name' => TABLE_HEADING_CUSTOMER_ID,
        'function' => function (&$row) {
          return $row['customer_id'] ?? TEXT_INFO_NA;
        },
      ],
      [
        'name' => TABLE_HEADING_PRODUCT_ID,
        'function' => function (&$row) {
          return $row['product_id'] ?? TEXT_INFO_NA;
        },
      ],
      [
        'name' => TABLE_HEADING_CREATED_AT,
        'class' => 'text-end',
        'function' => function (&$row) {
          return $row['created_at'];
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']))
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_EVENTS,
    'hooks' => [
      'button' => 'eventListButtons',
    ],
    'page' => $_GET['page'] ?? null,
    'web_id' => 'aID',
    'db_id' => 'id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => $analytics_sql,
  ];

  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['split']->display_table();

  if (isset($table_definition['info'])) {
    $sInfo = &$table_definition['info'];
  }
