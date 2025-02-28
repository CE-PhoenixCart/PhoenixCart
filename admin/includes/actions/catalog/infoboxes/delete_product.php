<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_DELETE_PRODUCT;

  $contents = ['form' => (new Form('products', $Admin->link('catalog.php', ['action' => 'delete_product_confirm', 'cPath' => $cPath])))->hide('products_id', $product->get('id'))];
  $contents[] = ['text' => TEXT_DELETE_PRODUCT_INTRO];
  $contents[] = ['class' => 'text-center text-uppercase fw-bold', 'text' => $product->get('name')];

  $tickable = (new Tickable('product_categories[]', ['class' => 'form-check-input'], 'checkbox'))->tick();
  $product_categories_string = '';
  foreach (Categories::generate_paths($product->get('categories')) as $i => $product_categories) {
    $category_path = implode('&nbsp;&gt;&nbsp;', array_column($product_categories, 'text'));

    $product_categories_string .= '<div class="form-check form-switch">';
      $product_categories_string .= $tickable->set('value', $product_categories[count($product_categories)-1]['id'])->set('id', 'dProduct_' . $i);
      $product_categories_string .= '<label for="dProduct_' . $i . '" class="form-check-label text-muted"><small>' . $category_path . '</small></label>';
    $product_categories_string .= '</div>';
  }

  $contents[] = ['text' => $product_categories_string];
  
  $contents[] = [
    'class' => 'd-grid',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger btn-lg mb-1'),
  ];
  
  $contents[] = [
    'class' => 'text-center',
    'text' => $Admin->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $Admin->link('catalog.php', ['cPath' => $cPath, 'pID' => $product->get('id')])),
  ];
