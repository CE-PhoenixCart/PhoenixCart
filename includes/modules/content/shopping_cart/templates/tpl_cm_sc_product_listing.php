<div class="<?= MODULE_CONTENT_SC_PRODUCT_LISTING_CONTENT_WIDTH ?> cm-sc-product-listing">
  <?= $form ?>
  <div class="table-responsive">
    <table class="table table-sm table-hover mb-0">
      <thead class="thead-light">
        <tr>
          <th class="d-none d-md-table-cell">&nbsp;</th>
          <th><?= MODULE_CONTENT_SC_PRODUCT_LISTING_HEADING_PRODUCT ?></th>
          <th class="d-none d-md-table-cell"><?= MODULE_CONTENT_SC_PRODUCT_LISTING_HEADING_AVAILABILITY ?></th>
          <th><?= MODULE_CONTENT_SC_PRODUCT_LISTING_HEADING_QUANTITY ?></th>
          <th class="text-right"><?= MODULE_CONTENT_SC_PRODUCT_LISTING_HEADING_PRICE ?></th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($products as $product) {
          echo new Input('products_id[]', ['value' => $product->get('uprid')], 'hidden');
          echo '<tr>';
            echo '<td class="d-none d-md-table-cell" style="width: 200px;">';
              echo '<a href="', $product->get('link'), '">', new Image('images/' . $product->get('image'), [], htmlspecialchars($product->get('name'))), '</a>';
            echo '</td>';
            echo '<th class="align-middle">';
              echo '<a href="', $product->get('link'), '">', $product->get('name'), '</a>';
              if (STOCK_CHECK == 'true' && $product->lacks_stock()) {
                $GLOBALS['any_out_of_stock'] = true;

                echo '<span class="d-md-none text-danger align-middle">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
              } 
              $attributes = $product->get('attributes');
              foreach (($product->get('attribute_selections') ?? []) as $option => $value) {
                echo '<small><br><i> - ', $attributes[$option]['name'], ' ', $attributes[$option]['values'][$value]['name'], '</i></small>';
              }
            echo '</th>';
            if (STOCK_CHECK == 'true' && $product->lacks_stock()) {
              $GLOBALS['any_out_of_stock'] = true;

              echo '<td class="d-none d-md-table-cell align-middle"><span class="text-danger">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span></td>';
            } else {
              echo '<td class="d-none d-md-table-cell align-middle">' . MODULE_CONTENT_SC_PRODUCT_LISTING_TEXT_IN_STOCK . '</td>';
            }
            echo '<td class="align-middle">';
              echo '<div class="input-group" style="width: 110px;">';
                echo new Input('cart_quantity[]', ['value' => $product->get('quantity'), 'min' => '0'], 'number');
                echo '<div class="input-group-append">', new Button(MODULE_CONTENT_SC_PRODUCT_LISTING_TEXT_BUTTON_UPDATE, '', 'btn-info'), '</div>';
              echo '</div>';
            echo '</td>';
            echo '<td class="text-right align-middle">', $product->format('final_price', $product->get('quantity')), '</td>';
            echo '<td class="text-right align-middle">', new Button(MODULE_CONTENT_SC_PRODUCT_LISTING_TEXT_BUTTON_REMOVE, '', 'btn-link btn-trash', [], $GLOBALS['Linker']->build('shopping_cart.php', ['action' => 'remove_product', 'products_id' => $product->get('uprid')])), '</td>';
          echo '</tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
  </form>
  <hr class="mt-0">
</div>	

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
