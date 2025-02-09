<div class="<?= MODULE_CONTENT_GDPR_IP_CONTENT_WIDTH ?> cm-gdpr-ip-addresses">
  <table class="table">
    <thead class="table-dark">
      <tr>
        <th colspan="2"><?= MODULE_CONTENT_GDPR_IP_PUBLIC_TITLE ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="w-50">
          <p class="text-center"><?= sprintf(MODULE_CONTENT_GDPR_IP_NUM_IPS, $port_my_data['YOU']['IP']['COUNT']) ?></p>
        </td>
        <td>
          <ul class="list-group">
            <?php
            foreach ($port_my_data['YOU']['IP']['LIST'] as $k) {
              echo '<li class="list-group-item">';
                echo '<span class="float-end"><a title="DELETE" role="button" id="delete" data-ip-id="' . $k . '" class="btn btn-sm btn-danger text-white btn-delete text-white btn-delete-ip">' . MODULE_CONTENT_GDPR_IP_DELETE . '</a></span>';
                echo $k;
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
