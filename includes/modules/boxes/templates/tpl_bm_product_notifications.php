<div class="card mb-2 bm-product-notifications">
  <div class="card-header"><?= MODULE_BOXES_PRODUCT_NOTIFICATIONS_BOX_TITLE ?></div>
  <div class="list-group list-group-flush">
    <?= $notification_exists
      ? '<a class="list-group-item list-group-item-action" href="' . $GLOBALS['Linker']->build()->retain_query_except()->set_parameter('action', 'notify_remove') . '"><i class="fas fa-times"></i> ' . sprintf(MODULE_BOXES_PRODUCT_NOTIFICATIONS_BOX_NOTIFY_REMOVE, Product::fetch_name($_GET['products_id'])) .'</a>'
      : '<a class="list-group-item list-group-item-action" href="' . $GLOBALS['Linker']->build()->retain_query_except()->set_parameter('action', 'notify') . '"><i class="fas fa-envelope"></i> ' . sprintf(MODULE_BOXES_PRODUCT_NOTIFICATIONS_BOX_NOTIFY, Product::fetch_name($_GET['products_id'])) .'</a>'
    ?>
  </div>
  <div class="card-footer"><a class="card-link" href="<?= $GLOBALS['Linker']->build('account_notifications.php') ?>"><?= MODULE_BOXES_PRODUCT_NOTIFICATIONS_VIEW ?></a></div>
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