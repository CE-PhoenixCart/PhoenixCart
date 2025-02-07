<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $form = new Form('mail', $Admin->link('mail.php', ['action' => 'send_email_to_user']));

/* Re-Post all POST'ed variables */
  foreach ($_POST as $key => $value) {
    if (!is_array($value)) {
      $form->hide($key, htmlspecialchars($value));
    }
  }

  echo $form;
?>

  <table class="table table-striped">
    <tr>
      <th><?= TEXT_CUSTOMER ?></th>
      <td><?= $mail_sent_to ?></td>
    </tr>
    <tr>
      <th><?= TEXT_FROM ?></th>
      <td><?= htmlspecialchars($_POST['from_name']) ?></td>
    </tr>
    <tr>
      <th><?= TEXT_FROM_ADDRESS ?></th>
      <td><?= htmlspecialchars($_POST['from_address']) ?></td>
    </tr>
    <tr>
      <th><?= TEXT_SUBJECT ?></th>
      <td><?= htmlspecialchars($_POST['subject']) ?></td>
    </tr>
    <tr>
      <th><?= TEXT_MESSAGE ?></th>
      <td><?= nl2br(htmlspecialchars($_POST['message'])) ?></td>
    </tr>
    <?= $admin_hooks->cat('formPreview') ?>
  </table>

  <div class="d-grid mt-2">
    <?= new Button(IMAGE_SEND_EMAIL, 'fas fa-paper-plane', 'btn-success btn-lg') ?>
  </div>
  
  <?= $Admin->button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-light mt-1', $Admin->link('mail.php')) ?>
</form>
