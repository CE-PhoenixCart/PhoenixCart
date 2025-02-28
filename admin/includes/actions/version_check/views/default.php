<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/
?>

<p class="lead"><?php printf(TITLE_INSTALLED_VERSION, $current_version); ?></p>

<div class="<?= $check_message['class'] ?>">
  <p class="lead"><?= $check_message['message'] ?></p>
</div>

<?php
if (!empty($new_versions)) {
  ?>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th><?= TABLE_HEADING_VERSION ?></th>
          <th><?= TABLE_HEADING_RELEASED ?></th>
          <th class="text-end"><?= TABLE_HEADING_ACTION ?></th>
        </tr>
      </thead>
      <tbody>
      <?php
      foreach ($new_versions as $version) {
        $date = DateTime::createFromFormat(DATE_ATOM, $version->date);
        ?>
        <tr>
          <td><?= '<a href="' . $version->link . '" target="_blank" rel="noreferrer">' . $version->title . '</a>' ?></td>
          <td><?= $date->format('l jS F, Y') ?></td>
          <td class="text-end"><?= '<a href="' . $version->link . '" target="_blank" rel="noreferrer"><i class="fas fa-info-circle text-info"></i></a>' ?></td>
        </tr>
        <?php
      }
      ?>
      </tbody>
    </table>
  </div>
  <?php
}
?>