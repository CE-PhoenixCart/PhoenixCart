<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Products {

    public static function list_options() {
      return $GLOBALS['db']->fetch_all(sprintf(<<<'EOSQL'
SELECT p.products_id AS id, pd.products_name AS text
 FROM products p, products_description pd
 WHERE p.products_id = pd.products_id AND pd.language_id = %d
 ORDER BY products_name
EOSQL
        , (int)$_SESSION['languages_id']));
    }

    public static function remove($product_id) {
      $product_image_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT p.products_image
 FROM products p
   LEFT JOIN products p2 ON p.products_image = p2.products_image AND p.products_id != p2.products_id
 WHERE p.products_id = %d AND p2.products_id IS NULL
EOSQL
        , (int)$product_id));
      $product_image = $product_image_query->fetch_assoc();

      if (isset($product_image['products_image'])
       && (is_file($image = DIR_FS_CATALOG . 'images/' . $product_image['products_image'])
        || is_link($image)))
      {
        @unlink($image);
      }

      $product_images_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT dpi.image
 FROM products_images dpi
   LEFT JOIN products_images pi ON dpi.image = pi.image AND dpi.products_id != pi.products_id
 WHERE dpi.products_id = %d AND pi.image IS NULL
EOSQL
        , (int)$product_id));
      while ($product_images = $product_images_query->fetch_assoc()) {
        $image = DIR_FS_CATALOG . 'images/' . $product_images['image'];

        if (is_file($image) || is_link($image)) {
          @unlink($image);
        }
      }

      $GLOBALS['db']->query("DELETE FROM products_images WHERE products_id = " . (int)$product_id);
      $GLOBALS['db']->query("DELETE FROM specials WHERE products_id = " . (int)$product_id);
      $GLOBALS['db']->query("DELETE FROM products_to_categories WHERE products_id = " . (int)$product_id);
      $GLOBALS['db']->query("DELETE FROM products_description WHERE products_id = " . (int)$product_id);
      $GLOBALS['db']->query("DELETE FROM products_attributes WHERE products_id = " . (int)$product_id);
      $GLOBALS['db']->query("DELETE FROM customers_basket WHERE products_id = " . (int)$product_id . " OR products_id LIKE '" . (int)$product_id . "{%'");
      $GLOBALS['db']->query("DELETE FROM customers_basket_attributes WHERE products_id = " . (int)$product_id . " OR products_id LIKE '" . (int)$product_id . "{%'");
      $GLOBALS['db']->query("DELETE r, rd FROM reviews r INNER JOIN reviews_description rd ON r.reviews_id = rd.reviews_id WHERE r.products_id = " . (int)$product_id);
      $GLOBALS['db']->query("DELETE FROM products WHERE products_id = " . (int)$product_id);
    }

    public static function select($name, $parameters = []) {
      $products = array_merge(
        [['id' => '', 'text' => '--- ' . IMAGE_SELECT . ' ---']],
        static::list_options());

      return new Select($name, $products, $parameters);
    }

    public static function select_discountable($name, $parameters = []) {
      $products_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT p.products_id, pd.products_name, p.products_price AS base_price,
       1 AS products_status, 0 AS is_special
 FROM products p INNER JOIN products_description pd ON p.products_id = pd.products_id
   LEFT JOIN specials s ON p.products_id = s.products_id
 WHERE pd.language_id = %d AND s.products_id IS NULL
 ORDER BY pd.products_name
EOSQL
        , (int)$_SESSION['languages_id']));
      while ($product = $products_query->fetch_assoc()) {
        $product = new Product($product);
        $options[] = [
          'id' => $product->get('id'),
          'text' => sprintf('%s (%s)', $product->get('name'), $product->format()),
        ];
      }

      return new Select($name, $options, $parameters);
    }

  }
