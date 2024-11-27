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
    <p class="card-title flex-grow-1 fs-5 fw-semibold mb-3"><a class="stretched-link" href="<?= $product->get('link') ?>"><?= $product->get('name') ?></a></p>
    <p class="card-subtitle mb-2 fs-6 fw-semibold text-body-secondary"><?= $product->hype_price() ?></p>
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
