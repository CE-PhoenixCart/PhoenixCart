<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  echo new Form('mail', $Admin->link('mail.php', ['action' => 'preview']));

  $customers = [];
  $customers[] = ['id' => '', 'text' => TEXT_SELECT_CUSTOMER];
  $customers[] = ['id' => '***', 'text' => TEXT_ALL_CUSTOMERS];
  $customers[] = ['id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS];

  $sql = $customer_data->add_order_by($customer_data->build_read(['sortable_name', 'email_address'], 'customers'), ['sortable_name']);
  $mail_query = $db->query($sql);
  while ($customers_values = $mail_query->fetch_assoc()) {
    $customers[] = [
      'id' => $customer_data->get('email_address', $customers_values),
      'text' => $customer_data->get('sortable_name', $customers_values) . ' (' . $customer_data->get('email_address', $customers_values) . ')',
    ];
  }
?>

  <div class="row mb-2" id="zCustomer">
    <label for="Customer" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_CUSTOMER ?></label>
    <div class="col-sm-9">
      <?= (new Select('customers_email_address', $customers, ['class' => 'form-select', 'id' => 'Customer']))->require()->set_selection($_GET['customer'] ?? '') ?>
    </div>
  </div>

  <div class="row mb-2" id="zFromName">
    <label for="FromName" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_FROM ?></label>
    <div class="col-sm-9">
      <?= (new Input('from_name', ['value' => STORE_OWNER, 'id' => 'FromName']))->require() ?>
    </div>
  </div>

  <div class="row mb-2" id="zFromAddress">
    <label for="FromAddress" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_FROM_ADDRESS ?></label>
    <div class="col-sm-9">
      <?= (new Input('from_address', ['value' => STORE_OWNER_EMAIL_ADDRESS, 'id' => 'FromAddress']))->require() ?>
    </div>
  </div>

  <div class="row mb-2" id="zSubject">
    <label for="Subject" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_SUBJECT ?></label>
    <div class="col-sm-9">
      <?= (new Input('subject', ['id' => 'Subject']))->require() ?>
    </div>
  </div>

  <div class="row mb-2" id="zMessage">
    <label for="Message" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_MESSAGE ?></label>
    <div class="col-sm-9">
      <?= (new Textarea('message', ['cols' => '60', 'rows' => '15', 'id' => 'Message']))->require() ?>
    </div>
  </div>

  <?= $admin_hooks->cat('formNew') ?>

  <div class="d-grid mt-2">
    <?= new Button(IMAGE_PREVIEW, 'fas fa-eye', 'btn-success btn-lg') ?>
  </div>

</form>
