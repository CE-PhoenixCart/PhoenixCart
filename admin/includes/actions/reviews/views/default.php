<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $reviews_sql = sprintf(<<<'EOSQL'
SELECT r.*, pd.products_name
 FROM reviews r LEFT JOIN products_description pd ON r.products_id = pd.products_id AND pd.language_id = %d
 ORDER BY r.date_added DESC
EOSQL
    , (int)$_SESSION['languages_id']);

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_PRODUCTS,
        'function' => function ($row) {
          return $row['products_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_RATING,
        'function' => function ($row) {
          return new star_rating((float)$row['reviews_rating']);
        },
      ],
      [
        'name' => TABLE_HEADING_DATE_ADDED,
        'function' => function ($row) {
          return Date::abridge($row['date_added']);
        },
      ],
      [
        'name' => TABLE_HEADING_STATUS,
        'function' => function ($row) {
          $flag_link = (clone $row['link'])->set_parameter('action', 'set_flag')->set_parameter('formid', $_SESSION['sessiontoken']);
          return ($row['reviews_status'] == '1')
                ? '<i class="fas fa-check-circle text-success"></i> <a href="' . $flag_link->set_parameter('flag', '0') . '"><i class="fas fa-times-circle text-muted"></i></a>'
                : '<a href="' . $flag_link->set_parameter('flag', '1') . '"><i class="fas fa-check-circle text-muted"></i></a> <i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) use ($customer_data) {
          return (isset($row['info']->reviews_id) && ($row['info']->reviews_id === $row['reviews_id']))
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_REVIEWS,
    'page' => $_GET['page'] ?? null,
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => $reviews_sql,
  ];

  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['link'] = $GLOBALS['link']->set_parameter('rID', $row['reviews_id']);
    if (!isset($table_definition['info']) && (!isset($_GET['rID']) || ($_GET['rID'] === $row['reviews_id']))) {
      $reviews_text = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT rd.*
 FROM reviews r INNER JOIN reviews_description rd ON r.reviews_id = rd.reviews_id
 WHERE r.reviews_id = %d
EOSQL
         , (int)$row['reviews_id']))->fetch_assoc();

      $product_image = $GLOBALS['db']->query("SELECT products_image FROM products WHERE products_id = " . (int)$row['products_id'])->fetch_assoc();
      $row['products_image'] = $product_image['products_image'];

      $reviews_average = $GLOBALS['db']->query("SELECT (AVG(reviews_rating) / 5 * 100) AS average_rating FROM reviews WHERE products_id = " . (int)$row['products_id'])->fetch_assoc();
      $row['average_rating'] = $reviews_average['average_rating'];

      $rInfo_array = array_merge($row, $reviews_text);
      $table_definition['info'] = new objectInfo($rInfo_array);
      $row['info'] = &$table_definition['info'];

      $row['onclick'] = (clone $row['link'])->set_parameter('action', 'preview');
      $row['css'] = ' class="table-active"';
    } else {
      $row['onclick'] = $row['link'];
      $row['css'] = '';
    }
  };

  $table_definition['split']->display_table();
  $rInfo = &$table_definition['info'];
?>
