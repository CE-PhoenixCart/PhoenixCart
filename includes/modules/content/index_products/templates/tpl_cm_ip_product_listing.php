<?php
  if (!empty($output)) {
?>

<div class="filter-list">
  <?= implode(PHP_EOL, $output) ?>

</div><br class="d-block d-sm-none">

<?php
  }
?>
<div class="col-sm-<?= (int)MODULE_CONTENT_IP_PRODUCT_LISTING_CONTENT_WIDTH ?> cm-ip-product-listing">
  <?php include $GLOBALS['Template']->map('product_listing.php', 'component'); ?>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
