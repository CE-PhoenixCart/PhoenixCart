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
      <thead class="table-dark">
        <tr>
          <th><?= TABLE_HEADING_DIRECTORIES ?></th>
          <th class="text-end"><?= TABLE_HEADING_RECOMMENDED ?></th>
        </tr>
      </thead>
      <tbody>
      <?php
      foreach (generate_phoenix_directories(DIR_FS_CATALOG) as $file) {
        if (empty($_GET['show_all']) && ($file['whitelisted'] == $file['writable'])) {
          continue;
        }
        ?>
        <tr>
          <td><?= Text::ltrim_once($file['name'], DIR_FS_CATALOG) ?></td>
          <td class="text-end"><i class="fas fa-<?=
            $file['whitelisted'] ? 'edit' : 'lock',
            ' me-2 text-',
            ($file['whitelisted'] == $file['writable']) ? 'success' : 'danger'
          ?>"></i></td>
        </tr>
        <?php
      }
      ?>
      </tbody>
    </table>
  </div>

  <p><?= sprintf(TEXT_DIRECTORY, DIR_FS_CATALOG) ?></p>