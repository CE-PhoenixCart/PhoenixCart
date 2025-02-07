<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if ($categories_count + $products_count > 0) {
    $link = $Admin->link();
    if (isset($cInfo) && is_object($cInfo)) {
// category info box contents
      Guarantor::ensure_global('category_tree');
      $link->set_parameter('cPath',
        $category_tree->find_path($category_tree->get_parent_id($cInfo->categories_id))
      )->set_parameter('cID', $cInfo->categories_id);

      $heading = $cInfo->categories_name;

      $contents[] = [
        'class' => 'text-center',
        'text' => $Admin->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'edit_category'))
                . $Admin->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2', $link->set_parameter('action', 'delete_category')),
      ];
      $contents[] = ['text' => TEXT_DATE_ADDED . ' ' . Date::abridge($cInfo->date_added)];
      if (!Text::is_empty($cInfo->last_modified)) {
        $contents[] = ['text' => TEXT_LAST_MODIFIED . ' ' . Date::abridge($cInfo->last_modified)];
      }
      $contents[] = ['text' => $Admin->catalog_image("images/{$cInfo->categories_image}", [], $cInfo->categories_name) . '<br>' . $cInfo->categories_image];

      $contents[] = ['class' => 'text-center', 'text' => $Admin->button(IMAGE_MOVE, 'fas fa-arrows-alt', 'btn-light', $link->set_parameter('action', 'move_category'))];
    } elseif (isset($product) && ($product instanceof Product)) {
// product info box contents
      $link->set_parameter('cPath', $cPath)->set_parameter('pID', $product->get('id'));
      $heading = $product->get('name');

      $contents[] = [
        'class' => 'text-center',
        'text' => $Admin->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'new_product'))
                . $Admin->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2', $link->set_parameter('action', 'delete_product')),
      ];
      $contents[] = ['text' => TEXT_DATE_ADDED . ' ' . Date::abridge($product->get('date_added'))];
      if (!Text::is_empty($product->get('last_modified'))) {
        $contents[] = ['text' => TEXT_LAST_MODIFIED . ' ' . Date::abridge($product->get('last_modified'))];
      }
      if (date('Y-m-d') < $product->get('date_available')) {
        $contents[] = ['text' => TEXT_DATE_AVAILABLE . ' ' . Date::abridge($product->get('date_available'))];
      }
      $contents[] = ['text' => $Admin->catalog_image('images/' . $product->get('image'), [], $product->get('name')) . '<br>' . $product->get('image')];
      $contents[] = ['text' => TEXT_PRODUCTS_PRICE_INFO . ' ' . $product->format('price') . '<br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $product->get('quantity')];
      $contents[] = ['text' => TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($product->get('review_percentile'), 2) . '%'];
      $contents[] = [
        'class' => 'text-center',
        'text' => $Admin->button(IMAGE_MOVE, 'fas fa-arrows-alt', 'btn-light me-2', (clone $link)->set_parameter('action', 'move_product'))
                . $Admin->button(IMAGE_COPY_TO, 'fas fa-copy', 'btn-light', $link->set_parameter('action', 'copy_to')),
      ];
    }
  } else {
    $heading = EMPTY_CATEGORY;

    $contents[] = ['text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS];
  }
