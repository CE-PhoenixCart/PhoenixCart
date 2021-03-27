<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $directory = DIR_FS_CATALOG . 'includes/actions/';

  require 'includes/template_top.php';
?>

  <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>

  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="thead-dark">
        <tr>
          <th><?= TABLE_HEADING_FILE ?></th>
          <th><?= TABLE_HEADING_ACTION ?></th>
          <th><?= TABLE_HEADING_CLASS ?></th>
          <th><?= TABLE_HEADING_METHOD ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach (array_diff(scandir($directory), ['.', '..']) as $file) {
          $action = pathinfo($file, PATHINFO_FILENAME);
          $class = "\\Phoenix\\Actions\\$action";

          foreach (get_class_methods($class) as $method) {
            ?>
            <tr>
              <td><?= $file ?></td>
              <td><?= $action ?></td>
              <td><?= $class ?></td>
              <td><?= $method ?></td>
            </tr>
          <?php
          }
        }
        ?>
      </tbody>
    </table>
  </div>

  <p><?= TEXT_ACTIONS_DIRECTORY . ' ' . DIR_FS_CATALOG . 'includes/actions/' ?></p>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
