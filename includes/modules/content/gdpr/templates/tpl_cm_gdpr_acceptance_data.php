<div class="<?= MODULE_CONTENT_GDPR_ACCEPTANCE_DATA_CONTENT_WIDTH ?> cm-gdpr-acceptance-data">

  <?php
  foreach ($agreed_pages as $k => $v) {
    ?>
    <table class="table table-striped">
      <thead class="table-dark">
        <tr>
          <th colspan="2"><?= sprintf(MODULE_CONTENT_GDPR_ACCEPTANCE_DATA_PUBLIC_TITLE, $port_my_data['YOU']['ACCEPTED']['DOCUMENT'][strtoupper($v)]['TITLE']) ?></th>
        </tr>
        <tr>
          <td colspan="2" class="bg-white text-dark"><?= sprintf(MODULE_CONTENT_GDPR_ACCEPTANCE_DATA_EXPLANATION, $port_my_data['YOU']['ACCEPTED']['DOCUMENT'][strtoupper($v)]['TITLE'], $port_my_data['YOU']['ACCEPTED']['DOCUMENT'][strtoupper($v)]['ACCEPTED']) ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w-50"><?= MODULE_CONTENT_GDPR_ACCEPTANCE_TERMS ?></th>
          <td><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#<?= $v ?>Modal"><?= MODULE_CONTENT_GDPR_ACCEPTANCE_TERMS_VIEW_BUTTON ?></button></td>
        </tr>
        <tr>
          <th><?= MODULE_CONTENT_GDPR_ACCEPTANCE_LANGUAGE ?></th>
          <td><?= ucfirst($port_my_data['YOU']['ACCEPTED']['DOCUMENT'][strtoupper($v)]['LANGUAGE']) ?></td>
        </tr>
      </tbody>
    </table>
    <?php
  }
  ?>
</div>

<?php
foreach ($agreed_pages as $k => $v) {
  $modal = [
    'name' => $v . 'Modal',
    'title' => $port_my_data['YOU']['ACCEPTED']['DOCUMENT'][strtoupper($v)]['TITLE'],
    'text' => $port_my_data['YOU']['ACCEPTED']['DOCUMENT'][strtoupper($v)]['TEXT'],
    'close_button' => MATC_BUTTON_CLOSE,
  ];

  ob_start();
  include Guarantor::ensure_global('Template')->map('modal.php', 'component');

  $GLOBALS['Template']->add_block(ob_get_clean(), 'footer_scripts');
}

/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
