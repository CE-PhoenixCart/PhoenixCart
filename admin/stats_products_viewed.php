<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  require 'includes/template_top.php';
?>

  <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>

  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="thead-dark">
        <tr>
          <th><?= TABLE_HEADING_NUMBER ?></th>
          <th><?= TABLE_HEADING_PRODUCTS ?></th>
          <th class="text-right"><?= TABLE_HEADING_VIEWED ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        $rows = 0;
        $products_sql = "SELECT p.products_id, pd.products_name, pd.products_viewed, l.name FROM products p, products_description pd, languages l WHERE p.products_id = pd.products_id AND l.languages_id = pd.language_id ORDER BY pd.products_viewed DESC";
        $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_sql, $products_query_numrows);
        $link = $Admin->link('catalog.php', ['action' => 'new_product_preview', 'read' => 'only', 'origin' => 'stats_products_viewed.php?page=' . (int)$_GET['page']]);
        $products_query = $db->query($products_sql);
        while ($products = $products_query->fetch_assoc()) {
          $rows++;
          ?>
          <tr onclick="document.location.href='<?= $link->set_parameter('pID', (int)$products['products_id']) ?>'">
            <td><?= str_pad($rows, 2, '0', STR_PAD_LEFT) ?>.</td>
            <td><?= '<a href="' . $link . '">' . $products['products_name'] . '</a> (' . $products['name'] . ')' ?></td>
            <td class="text-right"><?= $products['products_viewed'] ?></td>
          </tr>
          <?php
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="row">
    <div class="col-sm-6"><?= $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS) ?></div>
    <div class="col-sm-6 text-sm-right"><?= $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']) ?></div>
  </div>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
