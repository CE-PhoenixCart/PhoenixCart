<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $cdgInfo =& $GLOBALS['table_definition']['info'];
  $GLOBALS['link']->set_parameter('cdgID', (int)$cdgInfo->customer_data_groups_id);
  $heading = TEXT_INFO_HEADING_DELETE_CUSTOMER_DATA_GROUP;

  $contents = ['form' => new Form('customer_data_groups', (clone $GLOBALS['link'])->set_parameter('action', 'delete_confirm'))];
  $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];

  $cdg_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT
  cdg.customer_data_groups_name,
  l.directory,
  l.image,
  l.name
 FROM customer_data_groups cdg INNER JOIN languages l ON cdg.language_id = l.languages_id
 WHERE customer_data_groups_id = %d
EOSQL
    , (int)$cdgInfo->customer_data_groups_id));
  while ($cdg = $cdg_query->fetch_assoc()) {
    $contents[] = [
      'text' => $GLOBALS['Admin']->catalog_image("includes/languages/{$cdg['directory']}/images/{$cdg['image']}", [], $cdg['name'])
              . '&nbsp;<strong>' . $cdg['customer_data_groups_name'] . '</strong>',
    ];
  }

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
