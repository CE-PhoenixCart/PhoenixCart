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
  $contents[] = ['class' => 'text-center text-uppercase font-weight-bold', 'text' => $product->get('name')];

  $tickable = (new Tickable('product_categories[]', ['class' => 'custom-control-input'], 'checkbox'))->tick();
  $product_categories_string = '';
  foreach (Categories::generate_paths($product->get('categories')) as $i => $product_categories) {
    $category_path = implode('&nbsp;&gt;&nbsp;', array_column($product_categories, 'text'));

    $product_categories_string .= '<div class="custom-control custom-switch">';
      $product_categories_string .= $tickable->set('value', $product_categories[count($product_categories)-1]['id'])->set('id', 'dProduct_' . $i);
      $product_categories_string .= '<label for="dProduct_' . $i . '" class="custom-control-label text-muted"><small>' . $category_path . '</small></label>';
    $product_categories_string .= '</div>';
  }

  $contents[] = ['text' => $product_categories_string];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger btn-block btn-lg mb-1')
            . $Admin->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $Admin->link('catalog.php', ['cPath' => $cPath, 'pID' => $product->get('id')])),
  ];
