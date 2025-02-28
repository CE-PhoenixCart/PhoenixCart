<div class="<?= MODULE_CONTENT_GDPR_ORDERS_CONTENT_WIDTH ?> cm-gdpr-orders">
  <table class="table">
    <thead class="table-dark">
      <tr>
        <th colspan="2"><?= MODULE_CONTENT_GDPR_ORDERS_PUBLIC_TITLE ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="w-50">
          <p class="text-center"><?= sprintf(MODULE_CONTENT_GDPR_ORDERS_NUM_ORDERS, $port_my_data['YOU']['ORDER']['COUNT']) ?></p>
        </td>
        <td>
          <ul class="list-group">
            <?php
            $m = 0;
            foreach($port_my_data['YOU']['ORDER']['LIST'] as $k => $v) {
              echo '<li class="list-group-item">';
                echo '<span class="float-end">';
                  echo '<a class="btn btn-info text-white btn-sm" role="button" href="' . $GLOBALS['Linker']->build('account_history_info.php', ['order_id' => (int)$v['ID']]) . '">' . MODULE_CONTENT_GDPR_ORDERS_EACH_VIEW . '</a>';
                echo '</span>';
                echo sprintf(MODULE_CONTENT_GDPR_ORDERS_EACH, $v['TOTAL'], $v['DATE']) . '</li>';
              $m++;
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
