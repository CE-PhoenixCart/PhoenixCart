<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $hooks->register_pipeline('loginRequired');

  $message_stack_area = 'addressbook';

// needs to be included earlier to set the success message in the messageStack
  require language::map_to_translation('address_book_process.php');

  if (is_numeric($_GET['delete'] ?? null) && Form::validate_action_is('deleteconfirm', 2)) {
    if ((int)$_GET['delete'] == $customer->get_default_address_id()) {
      $messageStack->add_session('addressbook', WARNING_PRIMARY_ADDRESS_DELETION, 'warning');
    } else {
      $db->query("DELETE FROM address_book WHERE address_book_id = " . (int)$_GET['delete'] . " and customers_id = " . (int)$_SESSION['customer_id']);

      $messageStack->add_session($message_stack_area, SUCCESS_ADDRESS_BOOK_ENTRY_DELETED, 'success');
    }

    Href::redirect($Linker->build('address_book.php'));
  }

// error checking when updating or adding an entry
  if (Form::validate_action_is(['process', 'update'])) {
    $customer_details = $customer_data->process($customer_data->get_fields_for_page('address_book'));
    $hooks->cat('injectFormVerify');
    if (Form::is_valid()) {
      if ('update' === $_POST['action']) {
        $check_query = $db->query("SELECT * FROM address_book WHERE address_book_id = '" . (int)$_GET['edit'] . "' AND customers_id = " . (int)$_SESSION['customer_id'] . " LIMIT 1");
        if (mysqli_num_rows($check_query) === 1) {
          if ( 'on' === ($_POST['primary'] ?? null) ) {
            $table = 'both';
            $customer_details['default_address_id'] = (int)$_GET['edit'];
          } else {
            $table = 'address_book';
          }
          $customer_data->update($customer_details, ['address_book_id' => (int)$_GET['edit'], 'id' => (int)$_SESSION['customer_id']], $table);

          $messageStack->add_session($message_stack_area, SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED, 'success');
        }
      } else {
        if ($customer->count_addresses() < MAX_ADDRESS_BOOK_ENTRIES) {
          if (!isset($customer_details['id'])) {
            $customer_details['id'] = (int)$_SESSION['customer_id'];
          }
          $customer_data->add_address($customer_details);

          if ( 'on' === ($_POST['primary'] ?? null) ) {
            $customer_data->update(['default_address_id' => $customer_details['address_book_id']], ['id' => (int)$_SESSION['customer_id']], 'customers');
          }

          $messageStack->add_session($message_stack_area, SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED, 'success');
        }
      }

      Href::redirect($Linker->build('address_book.php'));
    }
  }

  if (is_numeric($_GET['edit'] ?? null)) {
    if (is_null($customer->fetch_to_address((int)$_GET['edit']))) {
      $messageStack->add_session($message_stack_area, ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

      Href::redirect($Linker->build('address_book.php'));
    }

    $page_heading = HEADING_TITLE_MODIFY_ENTRY;
    $navbar_title_3 = NAVBAR_TITLE_MODIFY_ENTRY;
    $navbar_link_3 = $Linker->build('address_book_process.php', ['edit' => $_GET['edit']]);
    $back_link = $Linker->build('address_book.php');
  } elseif (is_numeric($_GET['delete'] ?? null)) {
    if ($_GET['delete'] == $customer->get_default_address_id()) {
      $messageStack->add_session($message_stack_area, WARNING_PRIMARY_ADDRESS_DELETION, 'warning');

      Href::redirect($Linker->build('address_book.php'));
    } else {
      $check_query = $db->query("SELECT COUNT(*) AS total FROM address_book WHERE address_book_id = " . (int)$_GET['delete'] . " AND customers_id = " . (int)$_SESSION['customer_id']);
      $check = $check_query->fetch_assoc();

      if ($check['total'] < 1) {
        $messageStack->add_session($message_stack_area, ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

        Href::redirect($Linker->build('address_book.php'));
      }
    }

    $page_heading = HEADING_TITLE_DELETE_ENTRY;
    $navbar_title_3 = NAVBAR_TITLE_DELETE_ENTRY;
    $navbar_link_3 = $Linker->build('address_book_process.php', ['delete' => $_GET['delete']]);
  } else {
    if ($customer->count_addresses() >= MAX_ADDRESS_BOOK_ENTRIES) {
      $messageStack->add_session($message_stack_area, ERROR_ADDRESS_BOOK_FULL);

      Href::redirect($Linker->build('address_book.php'));
    }

    $entry = [];
    $page_heading = HEADING_TITLE_ADD_ENTRY;
    $navbar_title_3 = NAVBAR_TITLE_ADD_ENTRY;
    $navbar_link_3 = $Linker->build('address_book_process.php');

    $back_link = $_SESSION['navigation']->link_snapshot('address_book.php');
  }

  require $Template->map(__FILE__, 'page');

  require 'includes/application_bottom.php';
