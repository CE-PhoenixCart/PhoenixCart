<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $link = $Linker->build('checkout_process.php', [session_name() => $_POST['M_sid'], 'hash' => $_POST['M_hash']], false);
?>
<!DOCTYPE html>
<html <?= HTML_PARAMS ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?= CHARSET ?>" />
<title><?= htmlspecialchars($Template->get_title()) ?></title>
<meta http-equiv="refresh" content="3; URL=<?= $link ?>">
</head>
<body>
<h1 class="h3"><?= STORE_NAME ?></h1>

<p><?= MODULE_PAYMENT_RBSWORLDPAY_HOSTED_TEXT_SUCCESSFUL_TRANSACTION ?></p>

<form action="<?= $link ?>" method="post" target="_top">
  <p><input type="submit" value="<?= sprintf(MODULE_PAYMENT_RBSWORLDPAY_HOSTED_TEXT_CONTINUE_BUTTON, addslashes(STORE_NAME)) ?>" /></p>
</form>

<p>&nbsp;</p>

<WPDISPLAY ITEM=banner>

</body>
</html>
