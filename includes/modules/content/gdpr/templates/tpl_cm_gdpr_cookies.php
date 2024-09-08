<div class="<?= MODULE_CONTENT_GDPR_COOKIES_CONTENT_WIDTH ?> cm-gdpr-cookies">
  <table class="table table-striped">
    <thead class="thead-dark">
      <tr>
        <th colspan="2"><?= MODULE_CONTENT_GDPR_COOKIES_PUBLIC_TITLE ?></th>
      </tr>
      <tr>
        <td colspan="2"><?= MODULE_CONTENT_GDPR_COOKIES_EXPLANATION ?></td>
      </tr>
    </thead>
    <?php
    foreach ($port_my_data['YOU']['SITE']['COOKIES']['LIST'] as $k => $v) {
      echo '<tr>';
        echo '<th class="w-50">';
          echo $v['NAME'];
          if ($v['NAME'] == session_name()) {
            echo '<span class="badge badge-secondary ml-2">' . MODULE_CONTENT_GDPR_COOKIES_REQUIRED . '</span>';
          }
        echo '</th>';
        echo '<td>';
          if ($v['NAME'] != session_name()) {
            echo '<span class="float-right"><a role="button" data-cookie-sess="' . $v['NAME'] . '" class="btn btn-sm btn-danger text-white btn-delete text-white btn-delete-cookie">' . MODULE_CONTENT_GDPR_COOKIES_DELETE . '</a></span>';
          }
          echo $v['CONTENT'];
        echo '</td>';
      echo '</tr>';
    }
    ?>
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
