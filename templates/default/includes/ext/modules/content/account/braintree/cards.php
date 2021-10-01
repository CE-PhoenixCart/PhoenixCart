<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(MODULE_CONTENT_ACCOUNT_BRAINTREE_CARDS_NAVBAR_TITLE_1, $account_link);
  $breadcrumb->add(MODULE_CONTENT_ACCOUNT_BRAINTREE_CARDS_NAVBAR_TITLE_2, $cards_link);

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= MODULE_CONTENT_ACCOUNT_BRAINTREE_CARDS_HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size('cards') > 0) {
    echo $messageStack->output('cards');
  }
?>

  <?= MODULE_CONTENT_ACCOUNT_BRAINTREE_CARDS_TEXT_DESCRIPTION ?>

  <h4><?= MODULE_CONTENT_ACCOUNT_BRAINTREE_CARDS_SAVED_CARDS_TITLE ?></h4>

  <div class="contentText row align-items-center">

<?php
  $tokens_query = $db->query("SELECT id, card_type, number_filtered, expiry_date FROM customers_braintree_tokens WHERE customers_id = " . (int)$_SESSION['customer_id'] . " ORDER BY date_added");

  $cards_link->add_parameters(['action' => 'delete', 'formid' => $_SESSION['sessiontoken']]);
  if ( mysql_num_rows($tokens_query) > 0 ) {
    while ( $token = $tokens_query->fetch_assoc() ) {
?>

      <div class="col-sm-6"><strong><?= htmlspecialchars($token['card_type']) ?></strong>&nbsp;&nbsp;****<?=
        htmlspecialchars($token['number_filtered']) . '&nbsp;&nbsp;' . htmlspecialchars(substr($token['expiry_date'], 0, 2) . '/' . substr($token['expiry_date'], 2))
    ?></div>
      <div class="col-sm-6 text-right"><?= new Button(SMALL_IMAGE_BUTTON_DELETE, 'fas fa-trash', '', [], $cards_link->set_paramter('id', (int)$token['id'])) ?></div>

<?php
    }
  } else {
?>

    <div class="alert alert-danger col"><?= MODULE_CONTENT_ACCOUNT_BRAINTREE_CARDS_TEXT_NO_CARDS ?></div>

<?php
  }
?>

  </div>

  <div class="buttonSet">
    <?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', '', [], $account_link) ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
