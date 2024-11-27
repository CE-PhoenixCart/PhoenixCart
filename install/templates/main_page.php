<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= TITLE ?></title>
    <meta name="robots" content="noindex,nofollow" />
    <link rel="icon" type="image/png" href="images/icon_phoenix.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>
  <body>
    <div class="container-fluid bg-light border-bottom mb-2">
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start py-3">
          <a href="index.php">
            <img src="images/phoenix.png" title="<?= TEXT_SOFTWARE_NAME ?>" />
          </a>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <ul class="nav nav-underline justify-content-center justify-content-md-end">
            <li class="nav-item">
              <a class="nav-link link-body-emphasis" href="https://phoenixcart.org/" target="_blank" rel="noreferrer"><?= TEXT_WEBSITE ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link link-body-emphasis" href="https://phoenixcart.org/forum/" target="_blank" rel="noreferrer"><?= TEXT_SUPPORT ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link link-body-emphasis" href="https://phoenixcart.org/phoenixcartwiki/index.php?title=How_to_Install" target="_blank" rel="noreferrer"><?= TEXT_USER_GUIDE ?></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="container mb-2">
      <?php require "templates/pages/$page_contents" ?>
    </div>
    <footer class="bg-light border-top py-2 text-center">
      <?= sprintf(TEXT_COPYRIGHT, date('Y')) ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
