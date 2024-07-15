<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

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
  <div class="card-body">
    <h5 class="card-title"><a href="<?= $product->get('link') ?>"><?= $product->get('name') ?></a></h5>
    <h6 class="card-subtitle mb-2 text-muted"><?= $product->hype_price() ?></h6>
    <?= implode('<br>', $card['extra'] ?? []) ?>
  </div>

<?php
  if (count($buttons) > 0) {
?>

  <div class="card-footer bg-white pt-0 border-0">
    <div class="d-flex justify-content-between"><?= implode(PHP_EOL, $buttons) ?></div>
  </div>

<?php
  }
?>
