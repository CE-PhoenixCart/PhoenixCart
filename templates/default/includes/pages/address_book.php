<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('account.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('address_book.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4 mb-4"><?= HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size('addressbook') > 0) {
    echo $messageStack->output('addressbook');
  }
?>

  <h2 class="fs-4"><?= PRIMARY_ADDRESS_TITLE ?></h2>

  <p><?= PRIMARY_ADDRESS_DESCRIPTION ?></p>
  
  <ul class="list-group mb-3">
    <li class="list-group-item"><b><?= $customer->make_address_label($customer->get('default_address_id'), true, ' ', ', ') ?></b></li>
  </ul>

  <h2 class="fs-4"><?= ADDRESS_BOOK_TITLE ?></h2>

  <div class="alert alert-danger" role="alert"><?= sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES) ?></div>

  <ul class="list-group mb-3">
    <?php
    $addresses_query = $customer->get_all_addresses_query();
    while ($address = $addresses_query->fetch_assoc()) {
      ?>
      <li class="list-group-item d-flex justify-content-between p-2">
        <div class="w-75">
          <?= $customer_data->get_module('address')->format($address, true, ' ', ', ') ?><?= ($customer->get('default_address_id') == $address['address_book_id']) ? '&nbsp;<small><i>' . PRIMARY_ADDRESS . '</i></small>' : '' ?>
        </div>
        <div class="w-25 text-end">
          <?= new Button(SMALL_IMAGE_BUTTON_EDIT, 'fas fa-file', 'btn btn-sm btn-dark mb-1', [], $Linker->build('address_book_process.php', ['edit' => $address['address_book_id']])) . new Button(SMALL_IMAGE_BUTTON_DELETE, 'fas fa-trash-alt', 'btn btn-sm btn-danger ms-2 mb-1', [], $Linker->build('address_book_process.php', ['delete' => $address['address_book_id']])) ?>
        </div>
      </li>
      <?php
      }
    ?>
  </ul>

  <?php
  if ($customer->count_addresses() < MAX_ADDRESS_BOOK_ENTRIES) {
    ?>
    <div class="d-grid">
      <?= new Button(IMAGE_BUTTON_ADD_ADDRESS, 'fas fa-home', 'btn-success btn-lg', [], $Linker->build('address_book_process.php')) ?>
    </div>
    <?php
    }
  ?>
  
  <div class="my-2">
    <?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light', [], $Linker->build('account.php')) ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
