<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/
?>

<div class="row">
  <?php
  $base_dir = DIR_FS_CATALOG . 'templates';
  
  foreach (scandir($base_dir, SCANDIR_SORT_ASCENDING) as $template) {
    if ($template === '.' || $template === '..') {
      continue;
    }

    $path = $base_dir . '/' . $template;

    if (!is_dir($path)) {
      continue;
    }

    $manifest_file = $path . '/static/manifest.json';

    if (!file_exists($manifest_file)) {
      continue;
    }

    $manifest = json_decode(file_get_contents($manifest_file), true);

    if (!is_array($manifest)) {
      continue;
    }
    
    $name = $manifest['name'] ?? $template;
    $author = $manifest['author']['name'] ?? '';
    $version = $manifest['version'] ?? '';
    $image = $manifest['image'] ?? '';
    $code = $manifest['code'] ?? $template;
    
    $home = $manifest['author']['home'] ?? '';
    $support = $manifest['author']['support'] ?? '';
    $donation = $manifest['author']['donation'] ?? '';
    
    $phoenix = $manifest['compatibility']['phoenix'] ?? [];
    $php = $manifest['compatibility']['php'] ?? [];

    $phoenix_range = $phoenix['min'] ?? '';
    $phoenix_range .= isset($phoenix['max']) ? ' - ' . $phoenix['max'] : '+';

    $php_range = $php['min'] ?? '';
    $php_range .= isset($php['max']) ? ' - ' . $php['max'] : '+';
    ?>
  
    <div class="col-md-4 mb-3">
      <div class="card">
        <?= $GLOBALS['Admin']->catalog_image('templates/' . $code . '/static/' . $image, ['class' => 'card-img-top']) ?>
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">
              <?= htmlspecialchars($name); ?> <small class="text-muted lead"><?= htmlspecialchars($version) ?></small>
            </h5>
            <span class="text-muted lead"><?= htmlspecialchars($author); ?></span>
          </div>
          
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <?php
              if ($code === TEMPLATE_SELECTION) {
                echo $Admin->button(BUTTON_CURRENTLY_INSTALLED, 'fa-solid fa-check', 'btn-success', $Admin->link());
              }
              else {
                echo $Admin->button(BUTTON_INSTALL, 'fa-solid fa-circle-check', 'btn-warning', $Admin->link('templates.php', ['action' => 'install', 'tpl' => $code]));
              }
              ?>
            </div>
            <div class="d-flex gap-1">
              <?php
              if (!empty($home)) echo '<a href="' . $home . '" target="_blank"><i class="fa-solid fa-fw fa-house"></i></a>';
              if (!empty($support)) echo '<a href="' . $support . '" target="_blank"><i class="fa-solid fa-fw fa-circle-question"></i></a>';
              if (!empty($donation)) echo '<a href="' . $donation . '" target="_blank"><i class="fa-solid fa-fw fa-money-bills"></i></a>';
              ?>
            </div>
          </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
          <div>
            <span class="badge bg-dark">
              <?= sprintf(BADGE_PHOENIX_VERSION, htmlspecialchars($phoenix_range)) ?>
            </span>
          </div>
          <div>
            <span class="badge bg-dark">
              <?= sprintf(BADGE_PHP_VERSION, htmlspecialchars($php_range)) ?>
            </span>
          </div>
        </div>

      </div>
    </div>

    <?php
  }
  ?>

</div>