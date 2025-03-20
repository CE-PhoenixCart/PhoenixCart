<div class="<?= MODULE_CONTENT_GDPR_CART_CONTENT_WIDTH ?> cm-gdpr-cart">
  <table class="table">
    <thead class="table-dark">
      <tr>
        <th colspan="2"><?= MODULE_CONTENT_GDPR_CART_PUBLIC_TITLE ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="w-50">
          <p class="text-center"><?= sprintf(MODULE_CONTENT_GDPR_CART_NUM_PRODUCTS, $port_my_data['YOU']['CART']['COUNT']) ?></p>
        </td>
        <td>
          <ul class="list-group">
            <?php
            foreach ($port_my_data['YOU']['CART']['LIST'] as $k => $v) {
              echo '<li class="list-group-item">' . sprintf(MODULE_CONTENT_GDPR_CART_EACH, $v['QTY'], $v['NAME']) . '</li>';
            }
            ?>
          </ul>
        </td>
      </tr>
    </tbody>
  </table>
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
