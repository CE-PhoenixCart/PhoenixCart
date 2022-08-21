<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $cdgInfo =& $GLOBALS['table_definition']['info'];
  $heading = TEXT_INFO_HEADING_NEW_CUSTOMER_DATA_GROUP;

  $contents = ['form' => new Form('customer_data_groups', $GLOBALS['link']->set_parameter('action', 'insert'))];
  $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];
  $contents[] = [
    'text' => '<div class="custom-control custom-switch">'
            . (new Tickable('use_first', ['value' => '1', 'class' => 'custom-control-input', 'id' => 'cdUse'], 'checkbox'))->tick()
            . '<label for="cdUse" class="custom-control-label text-muted"><small>' . TEXT_INFO_USE_FIRST_FOR_ALL . '</small></label></div>'
  ];

  foreach (array_values(language::load_all()) as $lang) {
    $contents[] = [
      'text' => TEXT_INFO_CUSTOMER_DATA_GROUP_NAME . '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text">' 
              . $GLOBALS['Admin']->catalog_image("includes/languages/{$lang['directory']}/images/{$lang['image']}", [], $lang['name']) . '</span></div>'
              . new Input('customer_data_groups_name[' . $lang['id'] . ']') . '</div>'
    ];
    $contents[] = ['text' => sprintf(TEXT_INFO_VERTICAL_SORT_ORDER, null) . '<br>' . new Input('cdg_vertical_sort_order[' . $lang['id'] . ']')];
    $contents[] = ['text' => sprintf(TEXT_INFO_HORIZONTAL_SORT_ORDER, null) . '<br>' . new Input('cdg_horizontal_sort_order[' . $lang['id'] . ']')];
    $contents[] = ['text' => sprintf(TEXT_INFO_WIDTH, null) . '<br>' . new Input('customer_data_groups_width[' . $lang['id'] . ']', ['value' => '12'])];
  }

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success mr-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
