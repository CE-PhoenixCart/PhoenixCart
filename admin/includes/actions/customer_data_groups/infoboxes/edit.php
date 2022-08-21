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
  $heading = TEXT_INFO_HEADING_EDIT_CUSTOMER_DATA_GROUP;

  $contents = ['form' => new Form('customer_data_groups', (clone $GLOBALS['link'])->set_parameter('action', 'save'))];
  $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];
  $contents[] = [
    'text' => '<div class="custom-control custom-switch">'
            . (new Tickable('use_first', ['value' => '1', 'class' => 'custom-control-input', 'id' => 'cdUse'], 'checkbox'))->tick()
            . '<label for="cdUse" class="custom-control-label text-muted"><small>' . TEXT_INFO_USE_FIRST_FOR_ALL . '</small></label></div>',
  ];

  $cdg_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT
  cdg.customer_data_groups_name,
  cdg.cdg_vertical_sort_order,
  cdg.cdg_horizontal_sort_order,
  cdg.customer_data_groups_width,
  l.directory,
  l.image,
  l.name,
  l.languages_id AS id
 FROM customer_data_groups cdg INNER JOIN languages l ON cdg.language_id = l.languages_id
 WHERE customer_data_groups_id = %d
EOSQL
    , (int)$cdgInfo->customer_data_groups_id));
  while ($cdg = $cdg_query->fetch_assoc()) {
    $contents[] = [
      'text' => TEXT_INFO_CUSTOMER_DATA_GROUP_NAME
              . '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text">'
              . $GLOBALS['Admin']->catalog_image("includes/languages/{$cdg['directory']}/images/{$cdg['image']}", [], $cdg['name']) . '</span></div>'
              . new Input('customer_data_groups_name[' . $cdg['id'] . ']', ['value' => $cdg['customer_data_groups_name']]) . '</div>',
    ];
    $contents[] = ['text' => sprintf(TEXT_INFO_VERTICAL_SORT_ORDER, null) . '<br>' . new Input('cdg_vertical_sort_order[' . $cdg['id'] . ']', ['value' => $cdg['cdg_vertical_sort_order']])];
    $contents[] = ['text' => sprintf(TEXT_INFO_HORIZONTAL_SORT_ORDER, null) . '<br>' . new Input('cdg_horizontal_sort_order[' . $cdg['id'] . ']', ['value' => $cdg['cdg_horizontal_sort_order']])];
    $contents[] = ['text' => sprintf(TEXT_INFO_WIDTH, null) . '<br>' . new Input('customer_data_groups_width[' . $cdg['id'] . ']', ['value' => $cdg['customer_data_groups_width']])];
  }

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success mr-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
