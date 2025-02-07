<?php
/*
  $Id$

  CE Phoenix, E-Commerce Made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $current_version = Versions::get('Phoenix');

  $new_versions = [];
  $check_message = [];

  $feed = Web::load_xml('https://feeds.feedburner.com/phoenixCartUpdate');

  foreach ($feed->channel->item as $item) {
    $compared_version = preg_replace('/[^0-9.]/', '', $item->title);

    if (version_compare($current_version, $compared_version, '<')) {
      $new_versions[] = $item;
    }
  }

  if (empty($feed->channel->item)) {
    $check_message = ['class' => 'alert alert-warning', 'message' => VERSION_SERVER_FAILURE];
  } elseif (empty($new_versions)) {
    $check_message = ['class' => 'alert alert-success', 'message' => VERSION_RUNNING_LATEST];
  } else {
    $check_message = [
      'class' => 'alert alert-danger',
      'message' => sprintf(VERSION_UPGRADES_AVAILABLE, $new_versions[0]->title),
    ];
  }
  
  require 'includes/segments/process_action.php';

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?=
      $Admin->button(GET_HELP, '', 'btn-dark', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons')      
      ?>
    </div>
  </div>

<?php
  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
