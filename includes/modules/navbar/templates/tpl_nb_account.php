<li class="nav-item dropdown nb-account">
  <a class="nav-link" href="#" id="navDropdownAccount" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?= $navbarAccountText ?>
  </a>
  <div class="dropdown-menu<?= (('Right' === MODULE_NAVBAR_ACCOUNT_CONTENT_PLACEMENT) ? ' dropdown-menu-end' : '') ?>" aria-labelledby="navDropdownAccount">
    <?= isset($_SESSION['customer_id'])
      ? '<a class="dropdown-item" href="' . $GLOBALS['Linker']->build('logoff.php') . '">' . MODULE_NAVBAR_ACCOUNT_LOGOFF . '</a>' . PHP_EOL
      : ('<a class="dropdown-item" href="' . $GLOBALS['Linker']->build('login.php') . '">' . MODULE_NAVBAR_ACCOUNT_LOGIN . '</a>' . PHP_EOL
       . '<a class="dropdown-item" href="' . $GLOBALS['Linker']->build('create_account.php') . '">' . MODULE_NAVBAR_ACCOUNT_REGISTER . '</a>' . PHP_EOL)
    ?>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="<?= $GLOBALS['Linker']->build('account.php') ?>"><?= MODULE_NAVBAR_ACCOUNT ?></a>
    <a class="dropdown-item" href="<?= $GLOBALS['Linker']->build('account_history.php') ?>"><?= MODULE_NAVBAR_ACCOUNT_HISTORY ?></a>
    <a class="dropdown-item" href="<?= $GLOBALS['Linker']->build('address_book.php') ?>"><?= MODULE_NAVBAR_ACCOUNT_ADDRESS_BOOK ?></a>
    <a class="dropdown-item" href="<?= $GLOBALS['Linker']->build('account_password.php') ?>"><?= MODULE_NAVBAR_ACCOUNT_PASSWORD ?></a>
  </div>
</li>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
