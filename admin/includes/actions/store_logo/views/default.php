<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>

  <?php
  $logo_query = $GLOBALS['db']->query("SELECT * FROM configuration WHERE configuration_key LIKE '%_LOGO' ORDER BY sort_order");
  while ($logo = $logo_query->fetch_assoc()) {
    ?>
    <div class="row mb-3">
      <div class="col text-center">
        <div class="card text-center">
          <div class="card-body py-5">
            <?php
            if ($logo['configuration_value'] === FAVICON_LOGO) {
              $array = ['256', '192', '128', '16'];
              foreach ($array as $size) {
                echo $Admin->catalog_image('images/favicon/' .  $size . '_' . FAVICON_LOGO);
              }
            }
            else {
              echo $Admin->catalog_image('images/' .  $logo['configuration_value']);
            }
            ?>
          </div>
          <div class="card-footer">
            <small class="mt-2"><?= DIR_FS_CATALOG . 'images/' .  $logo['configuration_value'] ?></small>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="alert alert-info">
          <?= sprintf(constant('TEXT_EXISTING_' . $logo['configuration_key']), $logo['configuration_title']) ?>
        </div>
      </div>
    </div>
    <?php
  }
  ?>
  