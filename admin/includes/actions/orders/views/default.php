<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $orders_sql = sprintf(<<<'EOSQL'
SELECT o.*, s.orders_status_name, ot.text AS order_total
 FROM orders o INNER JOIN orders_total ot ON o.orders_id = ot.orders_id
   LEFT JOIN orders_status s ON o.orders_status = s.orders_status_id AND s.language_id = %d
 WHERE ot.class = 'ot_total'
EOSQL
    , (int)$_SESSION['languages_id']);
  if (isset($_GET['cID'])) {
    $orders_sql .= ' AND o.customers_id = ' . (int)Text::input($_GET['cID']);
  }
  if (!empty($_GET['status']) && is_numeric($_GET['status'])) {
    $orders_sql .= ' AND o.orders_status = ' . (int)Text::input($_GET['status']);
  }
  $listing_order = ' ORDER BY o.orders_id DESC';

  $parameters = [
    'orders_sql' => &$orders_sql,
    'listing_order' => &$listing_order,
  ];
  $admin_hooks->cat('injectSQL', $parameters);

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_OID,
        'function' => function (&$row) {
          return $row['orders_id'];
        },
      ],
      [
        'name' => TABLE_HEADING_CUSTOMERS,
        'function' => function (&$row) {
          return $row['customers_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_ORDER_TOTAL,
        'function' => function (&$row) {
          return strip_tags($row['order_total']);
        },
      ],
      [
        'name' => TABLE_HEADING_DATE_PURCHASED,
        'class' => 'text-end',
        'function' => function (&$row) {
          return $row['date_purchased'];
        },
      ],
      [
        'name' => TABLE_HEADING_STATUS,
        'class' => 'text-end',
        'function' => function (&$row) {
          return $row['orders_status_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return '<a href="' . $row['onclick'] . '"><i class="fas fa-cogs me-2 text-dark"></i></a>'
               . ((isset($row['info']->orders_id))
                ? '<i class="fas fa-chevron-circle-right text-info"></i>'
                : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>');
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_ORDERS,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'oID',
    'sql' => $orders_sql . $listing_order,
  ];

  $table_definition['split'] = new Paginator($table_definition);
  $link = $Admin->link()->retain_query_except(['action']);
  $table_definition['function'] = function (&$row) use ($link, &$table_definition) {
    $link->set_parameter('oID', $row['orders_id']);
    if (!isset($table_definition['info']) && (!isset($_GET['oID']) || ($_GET['oID'] == $row['orders_id']))) {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['onclick'] = (clone $link)->set_parameter('action', 'edit');
      $row['css'] = ' class="table-active"';
      $row['info']->link = clone $link;
    } else {
      $row['onclick'] = clone $link;
      $row['css'] = '';
    }
  };
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?=
      $Admin->button(GET_HELP, '', 'btn-dark', GET_HELP_LINK, ['newwindow' => true]),
      $Admin->button('<i class="fas fa-search"></i>', '', 'btn-light ms-2', $Admin->link('orders.php'), ['data-bs-toggle' => 'collapse', 'data-bs-target' => '#collapseSearch', 'aria-expanded' => 'false', 'aria-controls' => 'collapseSearch']),
      $admin_hooks->cat('extraButtons')
      ?>
    </div>
  </div>
  
  <div class="collapse row mb-1" id="collapseSearch">
    <div class="col-6 align-self-center">
      <?=
      $keywords = '';
      if (!Text::is_empty($_GET['oID'] ?? '')) {
        $keywords = Text::input($_GET['oID']);
      }

      echo (new Form('orders', $Admin->link('orders.php'), 'get'))->hide_session_id()->hide('action', 'edit'),
         '<div class="input-group mb-1">',
           '<span class="input-group-text">', HEADING_TITLE_SEARCH, '</span>',
           new Input('oID', ['value' => $keywords], 'number'),
         '</div>',
       '</form>'
      ?>
    </div>
    <div class="col-6 align-self-center">
      <?= (new Form('status', $Admin->link('orders.php'), 'get'))->hide_session_id(),
         '<div class="input-group mb-1">',
           '<span class="input-group-text">', HEADING_TITLE_STATUS, '</span>',
           new Select('status', array_merge([['id' => '', 'text' => TEXT_ALL_ORDERS]], order_status::fetch_options()), ['class' => 'form-select', 'onchange' => 'this.form.submit()']),
         '</div>',
       '</form>'
       ?>
    </div>
    <?= $admin_hooks->cat('injectFilterForm') ?>
  </div>

<?php
  $table_definition['split']->display_table();
?>
