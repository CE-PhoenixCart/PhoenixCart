<div class="card mb-2 bm-order-history">
  <div class="card-header"><?= MODULE_BOXES_ORDER_HISTORY_BOX_TITLE ?></div>
  <ul class="list-group list-group-flush">
    <?php
  while ($products = $products_query->fetch_assoc()) {
    echo '<li class="list-group-item d-flex justify-content-between align-items-center"><a href="',
         $GLOBALS['Linker']->build('product_info.php', ['products_id' => $products['products_id']]),
         '">', $products['products_name'], '</a><span class="badge"><a class="badge badge-primary" href="',
         $GLOBALS['Linker']->build()->retain_query_except()->set_parameter('action', 'cust_order')->set_parameter('pid', $products['products_id']),
         '"><i class="fas fa-shopping-cart fa-fw fa-2x"></i></a></span></li>';
  } ?>
  </ul>
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
