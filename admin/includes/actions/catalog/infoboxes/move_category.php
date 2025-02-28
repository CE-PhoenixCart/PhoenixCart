<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_MOVE_CATEGORY;

  $contents = ['form' => (new Form('categories', $Admin->link('catalog.php', ['action' => 'move_category_confirm', 'cPath' => $cPath])))->hide('categories_id', $cInfo->categories_id)];
  $contents[] = ['text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name)];
  $contents[] = ['text' => sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . (new Select('move_to_category_id', Categories::get_tree(), ['class' => 'form-select']))->set_selection($current_category_id)];
  
  $contents[] = [
    'class' => 'd-grid',
    'text' => new Button(IMAGE_MOVE, 'fas fa-arrows-alt', 'btn-success btn-lg mb-1'),
  ];
  
  $contents[] = [
    'class' => 'text-center',
    'text' => $Admin->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $Admin->link('catalog.php', ['cPath' => $cPath, 'cID' => $cInfo->categories_id])),
  ];
