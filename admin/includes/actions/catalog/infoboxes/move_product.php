<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_MOVE_PRODUCT;

  $contents = ['form' => (new Form('products', $Admin->link('catalog.php', ['action' => 'move_product_confirm', 'cPath' => $cPath])))->hide('products_id', $product->get('id'))];
  $contents[] = ['text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $product->get('name'))];
  $contents[] = ['text' => TEXT_INFO_CURRENT_CATEGORIES . '<br><i>' . Categories::draw_breadcrumbs($product->get('categories')) . '</i>'];
  $contents[] = [
    'text' => sprintf(TEXT_MOVE, $product->get('name')) . '<br>'
            . (new Select('move_to_category_id', $category_tree->get_selections([['id' => '0', 'text' => TEXT_TOP]]), ['class' => 'form-select']))->set_selection($current_category_id)];
  
  $contents[] = [
    'class' => 'd-grid',
    'text' => new Button(IMAGE_MOVE, 'fas fa-arrows-alt', 'btn-success btn-lg'),
  ];
  
  $contents[] = [
    'class' => 'text-center',
    'text' => $Admin->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $Admin->link('catalog.php', ['cPath' => $cPath, 'pID' => $product->get('id')])),
  ];
