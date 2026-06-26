<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  if (!isset($card) || !is_array($card)) {
    $card = [];
  }
  if (!isset($buttons) || !is_array($buttons)) {
    $buttons = [];
  }

  $parameters = [
    'product' => &$product,
    'card' => &$card,
    'buttons' => &$buttons,
    'tpl_data' => $tpl_data ?? [],
  ];
  $GLOBALS['hooks']->cat('injectProductCard', $parameters);
?>
<a href="<?= $product->get('link') ?>"><?= (new Image('images/' . $product->get('image'), [], htmlspecialchars($product->get('name'))))->append_css('card-img-top') ?></a>
  <div class="card-body d-flex flex-column" style="transform: rotate(0);">
    <p class="card-title fs-5 fw-semibold mb-3"><a class="stretched-link" href="<?= $product->get('link') ?>"><?= $product->get('name') ?></a></p>
    
    <div>
      <div class="d-flex align-items-center gap-2 text-body-secondary">
        <span class="fw-semibold"><?= $product->hype_price() ?></span>
        <span>·</span>
        <span><?= ($product->get('in_stock') <= 0 ? IS_PRODUCT_OOS : IS_PRODUCT_IIS); ?></span>
      </div>
      <?= implode('<br>', $card['extra'] ?? []) ?>
    </div>
  </div>

<?php
  if (count($buttons) > 0) {
?>

  <div class="card-footer">
    <div class="d-flex justify-content-between"><?= implode(PHP_EOL, $buttons) ?></div>
  </div>

<?php
  }
?>
