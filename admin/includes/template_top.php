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

<?= $admin_hooks->cat('injectSiteStart') ?>

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