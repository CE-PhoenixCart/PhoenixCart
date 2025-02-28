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
    'text' => '<div class="form-check form-switch">'
            . (new Tickable('use_first', ['value' => '1', 'class' => 'form-check-input', 'id' => 'cdUse'], 'checkbox'))->tick()
            . '<label for="cdUse" class="form-check-label text-muted"><small>' . TEXT_INFO_USE_FIRST_FOR_ALL . '</small></label></div>'
  ];

  foreach (array_values(language::load_all()) as $lang) {
    $contents[] = [
      'text' => TEXT_INFO_CUSTOMER_DATA_GROUP_NAME . '<div class="input-group"><span class="input-group-text">' 
              . $GLOBALS['Admin']->catalog_image("includes/languages/{$lang['directory']}/images/{$lang['image']}", [], $lang['name']) . '</span>'
              . new Input('customer_data_groups_name[' . $lang['id'] . ']', ['id' => "gName-{{$lang['code']}}"]) . '</div>'
    ];
    $contents[] = ['text' => sprintf(TEXT_INFO_SORT_ORDER, null) . '<br>' . new Input('cdg_vertical_sort_order[' . $lang['id'] . ']', ['id' => "gSort-{$lang['code']}"])];
    $contents[] = ['text' => sprintf(TEXT_INFO_WIDTH, null) . '<br>' . new Input('customer_data_groups_width[' . $lang['id'] . ']', ['value' => 'col-sm-12', 'id' => "gWidth-{$lang['code']}"])];
  }

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
