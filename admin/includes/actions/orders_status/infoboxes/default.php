<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($table_definition['info']->orders_status_id)) {
    $oInfo = &$table_definition['info'];
    $heading = $oInfo->orders_status_name;
    $link = $GLOBALS['link']->set_parameter('oID', $oInfo->orders_status_id);

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', $link->set_parameter('action', 'delete')),
    ];

    $orders_status_inputs_string = '';
    foreach (language::load_all() as $l) {
      $orders_status_inputs_string .= '<br>' . $GLOBALS['Admin']->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", [], $l['name'])
                                    . '&nbsp;' . order_status::fetch_name($oInfo->orders_status_id, $l['id']);
    }

    $contents[] = ['text' => $orders_status_inputs_string];
  }
