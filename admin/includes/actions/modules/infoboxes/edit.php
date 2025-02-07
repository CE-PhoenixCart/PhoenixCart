<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $mInfo =& $table_definition['info'];

  $keys = '';
  foreach ($mInfo->keys as $key => $value) {
    $keys .= '<strong>' . $value['title'] . '</strong><br>' . $value['description'] . '<br>';

    if ($value['set_function']) {
      eval('$keys .= ' . $value['set_function'] . "'" . addslashes($value['value']) . "', '" . $key . "');");
    } else {
      $keys .= new Input('configuration[' . $key . ']', ['value' => $value['value']]);
    }

    $keys .= '<br><br>';
  }
  $keys = html_entity_decode(stripslashes(Text::rtrim_once($keys, '<br><br>')));

  $heading = $mInfo->title;

  $contents = ['form' => new Form('modules', $Admin->link('modules.php')->retain_query_except()->set_parameter('action', 'save'))];
  $contents[] = ['text' => $keys];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $Admin->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $Admin->link('modules.php', ['set' => $set, 'module' => $_GET['module']])),
  ];
