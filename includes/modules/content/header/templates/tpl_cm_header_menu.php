<div class="<?= MODULE_CONTENT_HEADER_MENU_CONTENT_WIDTH ?> cm-header-menu">
  <nav class="navbar <?= $menu_style ?> cm-header-menu-navbar">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nbHeader" aria-controls="nbHeader" aria-expanded="false" aria-label="<?= MODULE_CONTENT_HEADER_MENU_TOGGLER ?>">
      <span class="navbar-toggler-icon"></span> <?= MODULE_CONTENT_HEADER_MENU_TOGGLER ?>
    </button>

    <div class="collapse navbar-collapse" id="nbHeader">
      <ul class="navbar-nav mr-auto">
        <?php
        foreach ($category_tree->get_children($category_tree->get_root_id()) as $e) {
          $l = $category_tree->get($e);
          $l['children'] = $category_tree->get_children($e);

          if ($l['children']) {
            echo '<li class="nav-item dropdown">';
              echo '<a class="nav-link dropdown-toggle" href="#" id="nbHeader_' . $l['id'] . '" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $l['name'] . '</a>';
              echo '<div class="dropdown-menu" aria-labelledby="nbHeader_' . $l['id'] . '">';
              foreach ($l['children'] as $c) {
                echo '<a class="dropdown-item" href="' . $GLOBALS['Linker']->build('index.php', ['cPath' => $category_tree->find_path($c)]) . '">' . $category_tree->get($c, 'name') . '</a>';
              }

              echo '</div>';
            echo '</li>';
          } else {
            echo '<li class="nav-item">';
              echo '<a class="nav-link" href="' . $GLOBALS['Linker']->build('index.php', ['cPath' => $l['id']]) . '">' . $l['name'] . '</a>';
            echo '</li>';
          }
        }
        ?>
      </ul>
      <?= $cm_header_menu_manufacturers ?>
    </div>
  </nav>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
