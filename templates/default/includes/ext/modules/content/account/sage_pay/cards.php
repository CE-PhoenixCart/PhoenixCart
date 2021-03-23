<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $account_link = $Linker->build('account.php');
  $cards_link = $Linker->build('ext/modules/content/account/sage_pay/cards.php');

  $breadcrumb->add(MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_NAVBAR_TITLE_1, "$account_link");
  $breadcrumb->add(MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_NAVBAR_TITLE_2, "$cards_link");

  $delete_button = new Button(SMALL_IMAGE_BUTTON_DELETE, 'fas fa-trash');
  $delete_button->set('href', (string)$cards_link->add_parameters([
    'action' => 'delete',
    'id' => (int)$tokens['id'],
    'formid' => md5($_SESSION['sessiontoken']),
  ]));
  $back_button = (new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left'))->set('href', "$account_link");

  require $oscTemplate->map_to_template('template_top.php', 'component');
?>

<h1 class="display-4"><?= MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size('cards') > 0) {
    echo $messageStack->output('cards');
  }
?>

  <?= MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_TEXT_DESCRIPTION ?>

  <h4><?= MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_SAVED_CARDS_TITLE ?></h4>

  <div class="contentText row align-items-center">

<?php
  $tokens_query = $db->query("SELECT id, card_type, number_filtered, expiry_date FROM customers_sagepay_tokens WHERE customers_id = " . (int)$_SESSION['customer_id'] . " ORDER BY date_added");

  if ( mysqli_num_rows($tokens_query) > 0 ) {
    while ( $tokens = $tokens_query->fetch_assoc() ) {
?>

    <div>
      <div class="col-sm-6"><strong><?= htmlspecialchars($tokens['card_type']) ?></strong>&nbsp;&nbsp;****<?= htmlspecialchars($tokens['number_filtered']) . '&nbsp;&nbsp;' . htmlspecialchars(substr($tokens['expiry_date'], 0, 2) . '/' . substr($tokens['expiry_date'], 2)) ?></div>
      <div class="col-sm-6 text-right"><?= $delete_button ?></div>
    </div>

<?php
    }
  } else {
?>

    <div class="alert alert-danger col"><?= MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_TEXT_NO_CARDS ?></div>

<?php
  }
?>

  </div>

  <div class="buttonSet">
    <?= $back_button ?>
  </div>

<?php
  require $oscTemplate->map_to_template('template_bottom.php', 'component');
?>
