<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_DELETE_SPECIALS;

  $link = $GLOBALS['Admin']->link('specials.php')->retain_query_except(['action']);
  $contents = ['form' => new Form('specials', (clone $link)->set_parameter('action', 'delete_confirm'))];
  $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
  $contents[] = ['text' => '<strong>' . $table_definition['info']->products_name . '</strong>'];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
