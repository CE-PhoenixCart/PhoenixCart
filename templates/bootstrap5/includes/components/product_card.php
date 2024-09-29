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
  <div class="card-body" style="transform: rotate(0);">
    <h5 class="card-title mb-3"><a class="stretched-link" href="<?= $product->get('link') ?>"><?= $product->get('name') ?></a></h5>
    <h6 class="card-subtitle mb-2 text-body-secondary"><?= $product->hype_price() ?></h6>
    <?= implode('<br>', $card['extra'] ?? []) ?>
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
