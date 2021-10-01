<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?= HTML_PARAMS ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?= CHARSET ?>">
<title><?= MODULE_PAYMENT_SAGE_PAY_DIRECT_3DAUTH_TITLE ?></title>
<base href="<?= HTTP_SERVER . DIR_WS_CATALOG ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<form name="form" action="<?= $_SESSION['sage_pay_direct_acsurl'] ?>" method="POST">
<input type="hidden" name="PaReq" value="<?= $_SESSION['sage_pay_direct_pareq'] ?>" />
<input type="hidden" name="TermUrl" value="<?= $Linker->build('ext/modules/payment/sage_pay/redirect.php') ?>" />
<input type="hidden" name="MD" value="<?= $_SESSION['sage_pay_direct_md'] ?>" />
<noscript>
<center><p><?= MODULE_PAYMENT_SAGE_PAY_DIRECT_3DAUTH_INFO ?></p><p><input type="submit" value="<?=  MODULE_PAYMENT_SAGE_PAY_DIRECT_3DAUTH_BUTTON ?>"/></p></center>
</noscript>
<script><!--
document.form.submit();
//--></script>
</body>
</html>
