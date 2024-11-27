<nav class="navbar <?= $navbar_style ?> cm-navbar" data-bs-theme="<?= BOOTSTRAP_THEME ?>">
  <div class="<?= BOOTSTRAP_CONTAINER ?>">
    <?php
    $Template =& Guarantor::ensure_global('Template');
    if ($Template->has_blocks('navbar_modules_home')) {
      echo $Template->get_blocks('navbar_modules_home');
    }
    
    echo '<a class="nav-link d-block d-sm-none me-2" data-bs-toggle="offcanvas" href="#offcanvasCart" role="button" aria-controls="offcanvasCart">';
      echo sprintf(NAVBAR_ICON_CART_CONTENTS, $_SESSION['cart']->count_contents(), '');
    echo '</a>';
    ?>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="collapseCoreNav" aria-labelledby="collapseCoreNavLabel">
      <div class="offcanvas-header bg-body-tertiary">
        <h5 class="offcanvas-title" id="collapseCoreNavLabel"><?= MODULE_CONTENT_NAVBAR_SITE_MENU ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="<?= IMAGE_BUTTON_CLOSE ?>"></button>
      </div>
      
      <div class="offcanvas-body justify-content-between">
        <?php
        if ($Template->has_blocks('navbar_modules_left')) {
          echo '<ul class="navbar-nav">' . PHP_EOL;
            echo $Template->get_blocks('navbar_modules_left');
          echo '</ul>' . PHP_EOL;
        }
        if ($Template->has_blocks('navbar_modules_center')) {
          echo '<ul class="navbar-nav">' . PHP_EOL;
            echo $Template->get_blocks('navbar_modules_center');
          echo '</ul>' . PHP_EOL;
        }
        if ($Template->has_blocks('navbar_modules_right')) {
          echo '<ul class="navbar-nav">' . PHP_EOL;
            echo $Template->get_blocks('navbar_modules_right');
          echo '</ul>' . PHP_EOL;
        }
        ?>
      </div>
    </div>
  </div>
</nav>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
