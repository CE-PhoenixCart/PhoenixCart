<div class="<?= MODULE_CONTENT_PI_MANUFACTURER_CONTENT_WIDTH ?> cm-pi-manufacturer">
  <div class="accordion">
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBrand" aria-expanded="true" aria-controls="collapseBrand">
          <?= MODULE_CONTENT_PI_MANUFACTURER_BRAND ?>
        </button>
      </h2>
      <div id="collapseBrand" class="accordion-collapse collapse show">
        <div class="accordion-body">
          <div class="row">
            <div class="col"><strong><?= $m['brand']['name'] ?></strong></div>
            <div class="col"><?= nl2br($m['brand']['address'] ?? '') ?></div>
            <div class="col"><?= $m['brand']['email'] ?? '' ?></div>
          </div>
        </div>
      </div>
    </div>
    <?php
    if (array_key_exists('importer', $m)) {
      ?>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseImporter" aria-expanded="false" aria-controls="collapseImporter">
            <?= MODULE_CONTENT_PI_MANUFACTURER_IMPORTER ?>
          </button>
        </h2>
        <div id="collapseImporter" class="accordion-collapse collapse">
          <div class="accordion-body">
            <div class="row">
              <div class="col"><strong><?= $m['importer']['name'] ?? '' ?></strong></div>
              <div class="col"><?= nl2br($m['importer']['address'] ?? '') ?></div>
              <div class="col"><?= $m['importer']['email'] ?? '' ?></div>
            </div>
          </div>
        </div>
      </div>
      <?php
    }
    ?>
  </div>
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
