<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2023 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_shop_siteWide_manufacturerFilter {

  public function listen_navItemFilters() {
    // optional Product List Filter
    $output = [];
    if (PRODUCT_LIST_FILTER > 0) {
      if (empty($_GET['manufacturers_id'])) {
        $filterlist_sql = sprintf(<<<'EOSQL'
SELECT DISTINCT m.manufacturers_id AS id, m.manufacturers_name AS text
FROM products p, products_to_categories p2c, manufacturers m
WHERE p.products_status = 1
 AND p.manufacturers_id = m.manufacturers_id
 AND p.products_id = p2c.products_id
 AND p2c.categories_id = %d
ORDER BY m.manufacturers_name
EOSQL
        , (int)$GLOBALS['current_category_id']);
      } else {
        $filterlist_sql = sprintf(<<<'EOSQL'
SELECT DISTINCT c.categories_id AS id, cd.categories_name AS text
FROM products p, products_to_categories p2c, categories c, categories_description cd
WHERE p.products_status = 1
 AND p.products_id = p2c.products_id
 AND p2c.categories_id = c.categories_id
 AND p2c.categories_id = cd.categories_id
 AND cd.language_id = %d AND p.manufacturers_id = %d
ORDER BY cd.categories_name
EOSQL
        , (int)$_SESSION['languages_id'], (int)$_GET['manufacturers_id']);
      }

      $options = $GLOBALS['db']->fetch_all($filterlist_sql);
      if (count($options) > 1) {
        $form = new Form('filter', $GLOBALS['Linker']->build('index.php', [], false), 'get');

        if (empty($_GET['manufacturers_id'])) {
          $form->hide('cPath', $GLOBALS['cPath']);
          $options = array_merge([['id' => '', 'text' => TEXT_ALL_MANUFACTURERS]], $options);
        } else {
          $form->hide('manufacturers_id', $_GET['manufacturers_id']);
          $options = array_merge([['id' => '', 'text' => TEXT_ALL_CATEGORIES]], $options);
        }

        $form->hide('sort', $_GET['sort']);

        $select = new Select('filter_id', $options, ['class' => 'border-0 custom-select', 'onchange' => 'this.form.submit()']);
        if (isset($_GET['filter_id'])) {
          $select->set_selection($_GET['filter_id']);
        }
        $form->hide_session_id();
        $output = [$form, $select, '</form>'];
      }
    }

    if (!empty($output)) {
      return '<li class="nav-item ml-auto">' . implode(PHP_EOL, $output) . '</li>';
    }
  }
}
