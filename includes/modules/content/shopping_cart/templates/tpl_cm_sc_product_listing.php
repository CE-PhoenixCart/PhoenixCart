<div class="col-sm-<?= (int)MODULE_CONTENT_SC_PRODUCT_LISTING_CONTENT_WIDTH ?> cm-sc-product-listing">
  <?= $form ?>
  <div class="table-responsive">
    <table class="table mb-0">
      <thead class="thead-light">
        <tr>
          <th class="d-none d-md-table-cell">&nbsp;</th>
          <th><?= MODULE_CONTENT_SC_PRODUCT_LISTING_HEADING_PRODUCT ?></th>
          <th><?= MODULE_CONTENT_SC_PRODUCT_LISTING_HEADING_AVAILABILITY ?></th>
          <th><?= MODULE_CONTENT_SC_PRODUCT_LISTING_HEADING_QUANTITY ?></th>
          <th class="text-right"><?= MODULE_CONTENT_SC_PRODUCT_LISTING_HEADING_PRICE ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
  foreach ($products as $product) {
    echo '<tr>';
    echo '<td class="d-none d-md-table-cell"><a href="', $product->get('link'), '">',
         new Image('images/' . $product->get('image'), [], htmlspecialchars($product->get('name')), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT),
         '</a></td>';
    echo '<th><a href="', $product->get('link'), '">', $product->get('name'), '</a>';
    $attributes = $product->get('attributes');
    foreach (($product->get('attribute_selections') ?? []) as $option => $value) {
      echo '<small><br><i> - ', $attributes[$option]['name'], ' ', $attributes[$option]['values'][$value]['name'], '</i></small>';
    }
    echo '</th>';

    if (STOCK_CHECK == 'true' && $product->lacks_stock()) {
      $GLOBALS['any_out_of_stock'] = true;

      echo '<td><span class="text-danger"><b>' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</b></span></td>';
    } else {
      echo '<td>' . MODULE_CONTENT_SC_PRODUCT_LISTING_TEXT_IN_STOCK . '</td>';
    }

    echo '<td>';
    echo '<div class="input-group">';
    echo new Input('cart_quantity[]', ['value' => $product->get('quantity'), 'style' => 'width: 65px;', 'min' => '0'], 'number');
    echo new Input('products_id[]', ['value' => $product->get('uprid')], 'hidden');
    echo '<div class="input-group-append">', new Button(MODULE_CONTENT_SC_PRODUCT_LISTING_TEXT_BUTTON_UPDATE, '', 'btn-info'), '</div>';
    echo '<div class="input-group-append">',
         new Button(MODULE_CONTENT_SC_PRODUCT_LISTING_TEXT_BUTTON_REMOVE, '', 'btn-danger', [], $GLOBALS['Linker']->build('shopping_cart.php', ['action' => 'remove_product', 'products_id' => $product->get('uprid')])),
         '</div>';
    echo '</div>';
    echo '</td>';
    echo '<td class="text-right">', $product->format('final_price', $product->get('quantity')), '</td>';
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

  Copyright (c) 2016:
    Dan Cole - @Dan Cole
    James Keebaugh - @kymation
    Lambros - @Tsimi
    Rainer Schmied - @raiwa

  All rights reserved.

  Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

  1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

  2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

  3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
?>
