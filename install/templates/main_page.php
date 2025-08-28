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
    <meta name="robots" content="noindex,nofollow">
    <link rel="icon" type="image/png" href="images/icon_phoenix.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer">
  </head>
  <body>
    <div class="container-fluid bg-light border-bottom mb-2">
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start py-3">
          <a href="index.php">
            <img src="images/phoenix.png" title="<?= TEXT_SOFTWARE_NAME ?>">
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
  </body>
</html>
