<div class="<?= MODULE_CONTENT_GDPR_CONTACT_DETAILS_CONTENT_WIDTH ?> cm-gdpr-contact-details">
  <table class="table table-striped table-hover">
    <thead class="table-dark">
      <tr>
        <th colspan="2"><?= MODULE_CONTENT_GDPR_CONTACT_DETAILS_PUBLIC_TITLE ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w-50"><?= MODULE_CONTENT_GDPR_CONTACT_DETAILS_EMAIL ?></th>
        <td><?= $port_my_data['YOU']['CONTACT']['EMAIL'] ?></td>
      </tr>
      <tr>
        <th><?= MODULE_CONTENT_GDPR_CONTACT_DETAILS_PHONE ?></th>
        <td><?= $port_my_data['YOU']['CONTACT']['PHONE'] ?></td>
      </tr>
      <tr>
        <th><?= MODULE_CONTENT_GDPR_CONTACT_DETAILS_FAX ?></th>
        <td><?= $port_my_data['YOU']['CONTACT']['FAX'] ?></td>
      </tr>
      <tr>
        <th><?= MODULE_CONTENT_GDPR_CONTACT_DETAILS_MAIN_ADDRESS ?></th>
        <td><?= $customer->make_address_label($customer->get('default_address_id'), true, ' ', '<br>') ?></td>
      </tr>
    </tbody>
  </table>
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
