<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_COPY_TO;

  $contents = ['form' => (new Form('copy_to', $Admin->link('catalog.php', ['action' => 'copy_to_confirm', 'cPath' => $cPath])))->hide('products_id', $product->get('id'))];
  $contents[] = ['text' => TEXT_INFO_COPY_TO_INTRO];
  $contents[] = ['text' => TEXT_INFO_CURRENT_CATEGORIES . '<br><i>' . Categories::draw_breadcrumbs($product->get('categories')) . '</i>'];
  $contents[] = ['text' => TEXT_CATEGORIES . '<br>' . (new Select('categories_id', $category_tree->get_selections([['id' => '0', 'text' => TEXT_TOP]]), ['class' => 'form-select']))->set_selection($current_category_id)];
  $contents[] = [
    'text' => TEXT_HOW_TO_COPY
            . '<br><div class="form-check">'
            . (new Tickable('copy_as', ['value' => 'link', 'id' => 'cLink', 'class' => 'form-check-input'], 'radio'))->tick()
            . '<label class="form-check-label" for="cLink">' . TEXT_COPY_AS_LINK
            . '</label></div><div class="form-check">'
            . (new Tickable('copy_as', ['value' => 'duplicate', 'id' => 'dLink', 'class' => 'form-check-input'], 'radio'))
            . '<label class="form-check-label" for="dLink">' . TEXT_COPY_AS_DUPLICATE . '</label></div>',
  ];
  
  $contents[] = [
    'class' => 'd-grid',
    'text' => new Button(IMAGE_COPY, 'fas fa-copy', 'btn-success btn-lg mb-1'),
  ];
  
  $contents[] = [
    'class' => 'text-center',
    'text' =>  $Admin->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $Admin->link('catalog.php', ['cPath' => $cPath, 'pID' => $product->get('id')])),
  ];
