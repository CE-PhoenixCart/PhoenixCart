<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $product = product_by_id::administer($_GET['pID']);
  $translations = $product->get('translations');

  foreach (language::load_all() as $l) {
      ?>

      <div class="row">
        <div class="col">
          <h1 class="display-4 mb-2"><?= $Admin->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", [], $l['name']) . '&nbsp;' . $translations[$l['id']]['name'] ?></h1>
        </div>
        <div class="col text-right align-self-center">
          <h1 class="display-4 mb-2"><?= $product->format('price') ?></h1>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-3 text-left text-sm-right font-weight-bold"><?= TEXT_PRODUCTS_DESCRIPTION ?></div>
        <div class="col-sm-9"><?= $translations[$l['id']]['description'] ?></div>
      </div>

      <div class="row">
        <div class="col-sm-3 text-left text-sm-right font-weight-bold"><?= TEXT_PRODUCTS_IMAGE ?></div>
        <div class="col-sm-9"><?= $Admin->catalog_image('images/' . $product->get('image')) ?></div>
      </div>

      <div class="row">
        <div class="col-sm-3 text-left text-sm-right font-weight-bold"><?= TEXT_PRODUCTS_URL ?></div>
        <div class="col-sm-9"><?= $translations[$l['id']]['url'] ?>&nbsp;</div>
      </div>

      <div class="row">
        <div class="col-sm-3 text-left text-sm-right font-weight-bold"><?= TEXT_PRODUCT_DATE_ADDED ?></div>
        <div class="col-sm-9"><?= $product->get('date_added') ?></div>
      </div>

      <div class="row">
        <div class="col-sm-3 text-left text-sm-right font-weight-bold"><?= TEXT_PRODUCT_DATE_AVAILABLE ?></div>
        <div class="col-sm-9"><?= $product->get('date_available') ?>&nbsp;</div>
      </div>
      <?php
  }

  if (isset($_GET['origin'])) {
    $pos_params = strpos($_GET['origin'], '?', 0);
    if ($pos_params) {
      $back_link = $Admin->link(
        substr($_GET['origin'], 0, $pos_params),
        phoenix_parameterize(substr($_GET['origin'], $pos_params + 1)));
    } else {
      $back_link = $Admin->link($_GET['origin']);
    }
  } else {
    $back_link = $Admin->link('catalog.php', ['cPath' => $cPath, 'pID' => (int)$product->get('id')]);
  }

  echo $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $back_link);
