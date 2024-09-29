<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>

<div class="row">
  <?= $Template->get_content('header') ?>
</div>

<?php
  if (isset($_GET['error_message']) && !Text::is_empty($_GET['error_message'])) {
?>
  <div class="alert alert-danger" role="alert">
    <a href="#" class="close fas fa-times" data-bs-dismiss="alert"></a>
    <?= htmlspecialchars(stripslashes(urldecode($_GET['error_message']))) ?>
  </div>
<?php
  }

  if (isset($_GET['info_message']) && !Text::is_empty($_GET['info_message'])) {
?>
  <div class="alert alert-info" role="alert">
    <a href="#" class="close fas fa-times" data-bs-dismiss="alert"></a>
    <?= htmlspecialchars(stripslashes(urldecode($_GET['info_message']))) ?>
  </div>
<?php
  }
?>
