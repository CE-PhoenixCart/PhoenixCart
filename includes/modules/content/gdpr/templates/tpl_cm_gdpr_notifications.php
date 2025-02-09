<div class="<?= MODULE_CONTENT_GDPR_NOTIFICATIONS_CONTENT_WIDTH ?> cm-gdpr-notifications">
  <table class="table">
    <thead class="table-dark">
      <tr>
        <th colspan="2"><?= MODULE_CONTENT_GDPR_NOTIFICATIONS_PUBLIC_TITLE ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="w-50">
          <p class="text-center"><?= sprintf(MODULE_CONTENT_GDPR_NOTIFICATIONS_NUM_NOTIFICATIONS, $port_my_data['YOU']['NOTIFICATION']['COUNT']) ?></p>
        </td>
        <td>
          <ul class="list-group">
            <?php
            foreach ($port_my_data['YOU']['NOTIFICATION']['LIST'] as $k => $v) {
              echo '<li class="list-group-item">';
                echo '<span class="float-end"><a title="DELETE" role="button" id="delete" data-notification-id="' . $v['PID'] . '" class="btn btn-sm btn-danger text-white btn-delete text-white btn-delete-notification">' . MODULE_CONTENT_GDPR_NOTIFICATIONS_DELETE . '</a></span>';
                printf(MODULE_CONTENT_GDPR_NOTIFICATIONS_EACH, $v['DATE'], $v['PRODUCT']);
              echo '</li>';
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
