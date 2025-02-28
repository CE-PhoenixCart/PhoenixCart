<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>

  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <?php
  foreach ( $contents as $site => $groups ) {
?>
      <thead class="table-dark">
        <tr>
          <th colspan="4"><?php printf(TABLE_HEADING_LOCATION, $site); ?></th>
        </tr>
      </thead>
      <thead class="table-light">
        <tr>
          <th><?= TABLE_HEADING_GROUP ?></th>
          <th><?= TABLE_HEADING_FILE ?></th>
          <th><?= TABLE_HEADING_METHOD ?></th>
          <th class="text-end"><?= TABLE_HEADING_VERSION ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
    foreach ( $groups as $group => $actions ) {
      foreach ( $actions as $action => $codes ) {
        foreach ( $codes as $code => $locations) {
          foreach ($locations as $location) {
            if (is_array($location)) {
              $file = implode('->', $location);
              $class = explode('::', $location[0])[0];
              $version = class_exists($class) ? (get_class_vars($class)['version'] ?? null) : null;
            } else {
              $file = "$code.php";
              $version = get_class_vars("hook_{$site}_{$group}_{$code}")['version'] ?? null;
            }
?>
        <tr>
          <td><?= $group ?></td>
          <td><?= $file ?></td>
          <td><?= $action ?></td>
          <td class="text-end"><?= $version ?? 'N/A' ?></td>
        </tr>
        <?php
          }
        }
      }
    }
  }
?>
      </tbody>
    </table>
  </div>

  <hr>

  <p><?= TEXT_HOOKS_DIRECTORY . ' ' . DIR_FS_CATALOG . 'includes/hooks/' ?></p>
  