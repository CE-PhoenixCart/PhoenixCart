<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
<!DOCTYPE html>
<html <?= HTML_PARAMS ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?= CHARSET ?>" />
<title><?= htmlspecialchars(TITLE) ?></title>
<base href="<?= HTTP_SERVER . DIR_WS_CATALOG ?>" />
</head>
<body>

<div style="text-align: center;">
  <?= new Image('ext/modules/payment/paypal/images/hss_load.gif') ?>
</div>

<form name="pphs" action="<?= $form_url ?>" method="post" <?= ($error ? 'target="_top"' : '') ?>>
  <input type="hidden" name="hosted_button_id" value="<?= (isset($_SESSION['pphs_result']['HOSTEDBUTTONID']) ? htmlspecialchars($_SESSION['pphs_result']['HOSTEDBUTTONID']) : '') ?>" />
</form>

<script>
  document.pphs.submit();
</script>

</body>
</html>
