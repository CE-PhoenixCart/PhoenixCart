<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($GLOBALS['table_definition']['info']->customer_data_groups_id)) {
    $cdgInfo =& $GLOBALS['table_definition']['info'];
    $GLOBALS['link']->set_parameter('cdgID', (int)$cdgInfo->customer_data_groups_id);
    $heading = $cdgInfo->customer_data_groups_name;

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $GLOBALS['link'])->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', $GLOBALS['link']->set_parameter('action', 'delete')),
    ];

    $cdg_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT
  cdg.*,
  l.*
 FROM customer_data_groups cdg INNER JOIN languages l ON cdg.language_id = l.languages_id
 WHERE customer_data_groups_id = %d
EOSQL
      , (int)$cdgInfo->customer_data_groups_id));
    while ($cdg = $cdg_query->fetch_assoc()) {
      $contents[] = ['text' => TEXT_INFO_CUSTOMER_DATA_GROUP_NAME . '<br>' . $GLOBALS['Admin']->catalog_image("includes/languages/{$cdg['directory']}/images/{$cdg['image']}", [], $cdg['name']) . '&nbsp;' . $cdg['customer_data_groups_name']];
      $contents[] = ['text' => sprintf(TEXT_INFO_SORT_ORDER, $cdg['cdg_vertical_sort_order'])];
      $contents[] = ['text' => sprintf(TEXT_INFO_WIDTH, $cdg['customer_data_groups_width'])];
    }
  }
