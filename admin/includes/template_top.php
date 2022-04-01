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
<meta charset="<?= CHARSET ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?= TITLE ?></title>
<base href="<?= HTTP_SERVER . DIR_WS_ADMIN ?>" />
<link rel="stylesheet" href="<?= $Admin->catalog('ext/jquery/ui/redmond/jquery-ui-1.10.4.min.css') ?>">

<?= $admin_hooks->cat('injectSiteStart') ?>

<script src="<?= $Admin->catalog('ext/jquery/ui/jquery-ui-1.10.4.min.js') ?>"></script>

<?php
  if (!Text::is_empty(JQUERY_DATEPICKER_I18N_CODE)) {
?>
<script src="<?= $Admin->catalog('ext/jquery/ui/i18n/jquery.ui.datepicker-' . JQUERY_DATEPICKER_I18N_CODE . '.js') ?>"></script>
<script>
$.datepicker.setDefaults($.datepicker.regional['<?= JQUERY_DATEPICKER_I18N_CODE ?>']);
</script>
<?php
  }
?>

</head>
<body>

<?= $admin_hooks->cat('injectBodyStart') ?>

<div class="container-fluid">
  <div class="row">

<?php
  if (isset($_SESSION['admin']) && file_exists('includes/header.php')) {
    require 'includes/header.php';
  }
?>

  <div id="contentText" class="col">

    <?php
    if ($messageStack->size > 0) {
      echo $messageStack->output();
    }
    ?>