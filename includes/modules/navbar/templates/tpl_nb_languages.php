<li class="nav-item dropdown nb-languages">
  <a class="nav-link" href="#" id="navDropdownLanguages" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?= MODULE_NAVBAR_LANGUAGES_SELECTED_LANGUAGE ?>
  </a>
  <div class="dropdown-menu<?= (('Right' === MODULE_NAVBAR_LANGUAGES_CONTENT_PLACEMENT) ? ' dropdown-menu-end' : '') ?>" aria-labelledby="navDropdownLanguages">
    <?php
    foreach ($lng->catalog_languages as $key => $value) {
      $image = Text::ltrim_once(language::map_to_translation("images/{$value['image']}", $value['directory']), DIR_FS_CATALOG);
      
      $active = ($lng->language['code'] == $key) ? ' active' : '';
      
      echo '<a class="dropdown-item' . $active . '" href="'
           . $GLOBALS['Linker']->build()->retain_query_except(['currency'])->set_parameter('language', $key)
           . '">'
           . (new Image($image, [], htmlspecialchars($value['name'])))->set_responsive(false)
           . ' ' . $value['name'] . '</a>' . PHP_EOL;
    }
    ?>
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
