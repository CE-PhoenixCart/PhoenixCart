<?php
/*
* $Id: cards.php
* $Loc: /templates/default/includes/ext/modules/content/account/stripe_sca/
*
* Name: StripeSCA
* Version: 1.70
* Release Date: 2025-03-02
* Author: Rainer Schmied
* 	 phoenixcartaddonsaddons.com / raiwa@phoenixcartaddons.com
*
* License: Released under the GNU General Public License
*
* Comments: Author: [Rainer Schmied @raiwa]
* Author URI: [www.phoenixcartaddons.com]
* 
* CE Phoenix, E-Commerce made Easy
* https://phoenixcart.org
* 
* Copyright (c) 2021 Phoenix Cart
* 
* 
*/

  $breadcrumb->add(MODULE_CONTENT_ACCOUNT_STRIPE_SCA_CARDS_NAVBAR_TITLE_1, "$account_link");
  $breadcrumb->add(MODULE_CONTENT_ACCOUNT_STRIPE_SCA_CARDS_NAVBAR_TITLE_2, "$cards_link");

  $delete_button = new Button(SMALL_IMAGE_BUTTON_DELETE, 'fas fa-trash');
  $back_button = (new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left'))->set('href', "$account_link");

  require $Template->map('template_top.php', 'component');
?>

  <h1 class="display-4"><?= MODULE_CONTENT_ACCOUNT_STRIPE_SCA_CARDS_HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size('cards') > 0) {
    echo $messageStack->output('cards');
  }
?>

  <?= MODULE_CONTENT_ACCOUNT_STRIPE_SCA_CARDS_TEXT_DESCRIPTION ?>

  <h4><?= MODULE_CONTENT_ACCOUNT_STRIPE_SCA_CARDS_SAVED_CARDS_TITLE ?></h4>

  <div class="contentText row align-items-center">

<?php
  $tokens_query = $db->query(sprintf(<<<'EOSQL'
SELECT id, card_type, number_filtered, expiry_date 
  FROM customers_stripe_tokens 
  WHERE customers_id = %s 
  ORDER BY date_added
EOSQL
            , (int)$_SESSION['customer_id']));

  if ( mysqli_num_rows($tokens_query) > 0 ) {
    while ( $tokens = $tokens_query->fetch_assoc() ) {
      
      $delete_button->set('href', (string)$cards_link->add_parameters([
        'action' => 'delete',
        'customer_id' => (int)$_SESSION['customer_id'],
        'id' => (int)$tokens['id'],
        'formid' => $_SESSION['sessiontoken'],
      ]));
?>

      <div class="col-sm-6 mb-2"><strong><?= htmlspecialchars($tokens['card_type']) ?></strong>&nbsp;&nbsp;****<?= htmlspecialchars($tokens['number_filtered']) . '&nbsp;&nbsp;' . htmlspecialchars(substr($tokens['expiry_date'], 0, 2) . '/' . substr($tokens['expiry_date'], 2)) ?></div>
      <div class="col-sm-6 mb-2 text-end"><?= $delete_button ?></div>

<?php
    }
  } else {
?>

    <div class="alert alert-danger col"><?= MODULE_CONTENT_ACCOUNT_STRIPE_SCA_CARDS_TEXT_NO_CARDS ?></div>

<?php
  }
?>

  </div>

  <div class="mt-3">
    <?= $back_button ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
