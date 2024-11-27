<div class="<?= MODULE_CONTENT_HEADER_MENU_CONTENT_WIDTH ?> cm-header-menu">
  <nav class="navbar <?= $menu_style ?> cm-header-menu-navbar">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nbHeader" aria-controls="nbHeader" aria-expanded="false" aria-label="<?= MODULE_CONTENT_HEADER_MENU_TOGGLER ?>">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <span class="lead d-block d-sm-none"><?= MODULE_CONTENT_HEADER_MENU_TOGGLER ?></span>

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
      <?php
      if (MODULE_CONTENT_HEADER_MENU_MANUFACTURERS == 'True') {
        $cm_manufacturers_query = $GLOBALS['db']->query("SELECT manufacturers_id, manufacturers_name FROM manufacturers ORDER BY manufacturers_name");

        if (mysqli_num_rows($cm_manufacturers_query)) {
          echo '<ul class="navbar-nav ml-auto">';
            echo '<li class="nav-item dropdown">';
              echo '<a class="nav-link dropdown-toggle" href="#" id="cm-hm-brands" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . MODULE_CONTENT_HEADER_MENU_MANUFACTURER_DROPDOWN . '</a>';
              echo '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="cm-hm-brands">';
                while ($cm_manufacturers = $cm_manufacturers_query->fetch_assoc()) {
                  echo '<a class="dropdown-item" href="' . $GLOBALS['Linker']->build('index.php', ['manufacturers_id' => (int)$cm_manufacturers['manufacturers_id']]) . '">' . $cm_manufacturers['manufacturers_name'] . '</a>';
                }
              echo '</div>';
            echo '</li>' . PHP_EOL;
          echo '</ul>' . PHP_EOL;
        }
      }
      ?>
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
