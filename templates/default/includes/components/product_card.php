  <a href="<?= $product->get('link') ?>"><?= (new Image('images/' . $product->get('image'), [], htmlspecialchars($product->get('name'))))->append_css('card-img-top') ?></a>
  <div class="card-body">
    <h5 class="card-title"><a href="<?= $product->get('link') ?>"><?= $product->get('name') ?></a></h5>
    <h6 class="card-subtitle mb-2 text-muted"><?= $product->hype_price() ?></h6>
    <?= $card['extra'] ?? '' ?>
  </div>

<?php
  if ($card['show_buttons'] ?? false) {
?>

  <div class="card-footer bg-white pt-0 border-0">
    <div class="btn-group" role="group">
      <?php
    echo new Button(IS_PRODUCT_BUTTON_VIEW, '', 'btn-info btn-product-listing btn-view', [], $product->get('link'));

    if (!$product->get('has_attributes')) {
      echo PHP_EOL, new Button(
        IS_PRODUCT_BUTTON_BUY,
        '',
        'btn-light btn-product-listing btn-buy',
        ['data-has-attributes' => '0', 'data-in-stock' => (int)$product->get('in_stock'), 'data-product-id' => (int)$product->get('id')],
        $GLOBALS['Linker']->build()->retain_query_except()->set_parameter('action', 'buy_now')->set_parameter('products_id', (int)$product->get('id')));
    }
?>
    </div>
  </div>

<?php
  }

/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
