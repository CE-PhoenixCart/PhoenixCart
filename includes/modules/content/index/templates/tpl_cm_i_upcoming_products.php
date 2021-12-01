<div class="col-sm-<?= MODULE_CONTENT_UPCOMING_PRODUCTS_CONTENT_WIDTH ?> cm-i-upcoming-products">
  <table class="table table-striped table-sm">
    <tbody>
      <tr>
        <th><?= MODULE_CONTENT_UPCOMING_PRODUCTS_TABLE_HEADING_PRODUCTS ?></th>
        <th class="text-right"><?= MODULE_CONTENT_UPCOMING_PRODUCTS_TABLE_HEADING_DATE_EXPECTED ?></th>
      </tr>
      <?php
      $link = $GLOBALS['Linker']->build('product_info.php');
      while ($expected = $expected_query->fetch_assoc()) {
        $link->set_parameter('products_id', (int)$expected['products_id']);
        echo '<tr>';
        echo '  <td><a href="' . $link . '">' . $expected['products_name'] . '</a></td>';
        echo '  <td class="text-right">' . Date::abridge($expected['date_expected']) . '</td>';
        echo '</tr>';
      }
      ?>
    </tbody>
  </table>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
