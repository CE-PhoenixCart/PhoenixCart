<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Categories {

    public static function get_path($current_category_id = '') {
      if (empty($GLOBALS['cPath_array'])) {
        return $current_category_id;
      }

      if ('' === $current_category_id) {
        return implode('_', $GLOBALS['cPath_array']);
      }

      return Guarantor::ensure_global('category_tree')->find_path($current_category_id);
    }

    public static function get_tree($parent_id = '0', $spacing = '', $exclude = '', $categories = []) {
      $category_tree =& Guarantor::ensure_global('category_tree');

      if ( (count($categories) < 1) && ($exclude !== '0') ) {
        $categories[] = ['id' => '0', 'text' => TEXT_TOP];
      }

      return $category_tree->get_selections($categories, $parent_id);
    }

    public static function generate_paths($ids, $categories = []) {
      foreach ($ids as $index => $id) {
        $categories[$index] = ('0' == $id)
                            ? [['id' => '0', 'text' => TEXT_TOP]]
                            : static::generate_path($id, $categories, $index);
      }

      return $categories;
    }

    public static function generate_path($id) {
      $category_tree =& Guarantor::ensure_global('category_tree');

      $ancestors = array_reverse($category_tree->get_ancestors($id));
      $ancestors[] = $id;

      $categories = [];
      foreach ($ancestors as $category_id) {
        $categories[] = [
          'id' => $category_id,
          'text' => $category_tree->get($category_id, 'name'),
        ];
      }

      return $categories;
    }

    public static function draw_breadcrumbs($ids) {
      $tree =& Guarantor::ensure_global('category_tree');

      return implode('<br />', array_map(function ($c) use ($tree) {
        return implode('&nbsp;&gt;&nbsp;', array_map(function ($id) use ($tree) {
          return $tree->get($id, 'name');
        }, array_merge(array_reverse($tree->get_ancestors($c)), [$c])));
      }, $ids)) ?: TEXT_TOP;
    }

    public static function build_paths($ids) {
      $display = new tree_display(Guarantor::ensure_global('category_tree'));

      return implode('<br />', array_map(function ($id) use ($display) {
        return $display->buildBreadcrumb($id);
      }, $ids)) ?: TEXT_TOP;
    }

    public function remove($category_id) {
      $duplicate_image_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT c1.categories_image
 FROM categories c1
   LEFT JOIN categories c2
     ON c1.categories_image = c2.categories_image
    AND c1.categories_id != c2.categories_id
 WHERE c1.categories_id = %d AND c2.categories_id IS NULL
EOSQL
        , (int)$category_id));
      $duplicate_image = $duplicate_image_query->fetch_assoc();

      if (isset($duplicate_image['categories_image'])
       && (is_file($image = DIR_FS_CATALOG . 'images/' . $duplicate_image['categories_image'])
        || is_link($image)))
      {
        @unlink($image);
      }

      $GLOBALS['db']->query("DELETE FROM categories WHERE categories_id = " . (int)$category_id);
      $GLOBALS['db']->query("DELETE FROM categories_description WHERE categories_id = " . (int)$category_id);
      $GLOBALS['db']->query("DELETE FROM products_to_categories WHERE categories_id = " . (int)$category_id);
    }

  }
