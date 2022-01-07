<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('account.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('address_book.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size('addressbook') > 0) {
    echo $messageStack->output('addressbook');
  }
?>

  <h4><?= PRIMARY_ADDRESS_TITLE ?></h4>

  <div class="row">

    <div class="col-sm-8">
      <div class="alert alert-info" role="alert"><?= PRIMARY_ADDRESS_DESCRIPTION ?></div>
    </div>

    <div class="col-sm-4">
      <div class="card mb-2 text-white bg-info">
        <div class="card-header"><?= PRIMARY_ADDRESS_TITLE ?></div>

        <div class="card-body"><?= $customer->make_address_label($customer->get('default_address_id'), true, ' ', '<br>') ?></div>
      </div>
    </div>

  </div>

  <div class="w-100"></div>

  <h4><?= ADDRESS_BOOK_TITLE ?></h4>

  <div class="alert alert-danger" role="alert"><?= sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES) ?></div>

  <div class="row">
    <?php
    $addresses_query = $customer->get_all_addresses_query();
    while ($address = $addresses_query->fetch_assoc()) {
      ?>
      <div class="col-sm-4">
        <div class="card mb-2 <?= ($address['address_book_id'] == $customer->get('default_address_id')) ? 'text-white bg-info' : 'bg-light' ?>">
          <div class="card-header"><?= htmlspecialchars($customer_data->get('name', $address)) ?></strong><?= ($customer->get('default_address_id') == $address['address_book_id']) ? '&nbsp;<small><i>' . PRIMARY_ADDRESS . '</i></small>' : '' ?></div>
          <div class="card-body">
            <?= $customer_data->get_module('address')->format($address, true, ' ', '<br>') ?>
          </div>
          <div class="card-footer text-center"><?=
            new Button(SMALL_IMAGE_BUTTON_EDIT, 'fas fa-file', 'btn btn-dark btn-sm', [], $Linker->build('address_book_process.php', ['edit' => $address['address_book_id']])) . ' '
          . new Button(SMALL_IMAGE_BUTTON_DELETE, 'fas fa-trash-alt', 'btn btn-dark btn-sm', [], $Linker->build('address_book_process.php', ['delete' => $address['address_book_id']]))
          ?></div>
        </div>
      </div>
      <?php
      }
    ?>
  </div>

  <div class="buttonSet">
    <?php
    if ($customer->count_addresses() < MAX_ADDRESS_BOOK_ENTRIES) {
      ?>
      <div class="text-right"><?= new Button(IMAGE_BUTTON_ADD_ADDRESS, 'fas fa-home', 'btn-success btn-lg btn-block', [], $Linker->build('address_book_process.php')) ?></div>
      <?php
      }
    ?>
    <p><?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', '', [], $Linker->build('account.php')) ?></p>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
