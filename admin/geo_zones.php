<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';
  $link = $Admin->link()->retain_query_except(['action', 'saction', 'zID', 'sID']);

  $saction = $_GET['saction'] ?? '';

  $admin_hooks->call('geo_zones', 'preSaction');

  if (!Text::is_empty($saction)) {
    switch ($saction) {
      case 'insert_sub':
        $zID = Text::input($_GET['zID']);
        $zone_country_id = Text::input($_POST['zone_country_id']);
        $zone_id = Text::input($_POST['zone_id']);

        $db->query("INSERT INTO zones_to_geo_zones (zone_country_id, zone_id, geo_zone_id, date_added) VALUES (" . (int)$zone_country_id . ", " . (int)$zone_id . ", " . (int)$zID . ", NOW())");
        $new_subzone_id = mysqli_insert_id($db);

        $admin_hooks->call('geo_zones', 'insertSubSaction');

        Href::redirect($link->set_parameter('zID', (int)$zID)->set_parameter('action', 'list')->set_parameter('sID', $new_subzone_id));
        break;
      case 'save_sub':
        $sID = Text::input($_GET['sID']);
        $zID = Text::input($_GET['zID']);
        $zone_country_id = Text::input($_POST['zone_country_id']);
        $zone_id = Text::input($_POST['zone_id']);

        $db->query("UPDATE zones_to_geo_zones SET geo_zone_id = " . (int)$zID . ", zone_country_id = " . (int)$zone_country_id . ", zone_id = " . (Text::is_empty($zone_id) ? 'NULL' : (int)$zone_id) . ", last_modified = NOW() WHERE association_id = " . (int)$sID);

        $admin_hooks->call('geo_zones', 'saveSubSaction');

        Href::redirect($link->set_parameter('zID', (int)$zID)->set_parameter('action', 'list')->set_parameter('sID', $_GET['sID']));
        break;
      case 'delete_confirm_sub':
        $sID = Text::input($_GET['sID']);

        $db->query("DELETE FROM zones_to_geo_zones WHERE association_id = " . (int)$sID);

        $admin_hooks->call('geo_zones', 'deleteConfirmSubSaction');

        Href::redirect($link->set_parameter('zID', (int)$_GET['zID'])->set_parameter('action', 'list'));
        break;
    }
  }

  $admin_hooks->call('geo_zones', 'postSaction');

  require 'includes/segments/process_action.php';

  require 'includes/template_top.php';

  if (isset($_GET['zID']) && (('edit' === $saction) || ('new' === $saction))) {
?>
<script><!--
function update_zone(theForm) {
  var NumState = theForm.zone_id.options.length;
  var SelectedCountry = "";

  while(NumState > 0) {
    NumState--;
    theForm.zone_id.options[NumState] = null;
  }

  SelectedCountry = theForm.zone_country_id.options[theForm.zone_country_id.selectedIndex].value;

<?= new zone_js('SelectedCountry', 'theForm', 'zone_id') ?>

}
//--></script>
<?php
  }
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE . (isset($_GET['zID']) ? ' <small>' . geo_zone::fetch_name($_GET['zID']) . '</small>' : '') ?></h1>
    </div>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?=
      $Admin->button(GET_HELP, '', 'btn-dark me-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      empty($action)
      ? $Admin->button(TEXT_INFO_HEADING_NEW_ZONE, 'fas fa-atlas', 'btn-danger', (clone $link)->set_parameter('action', 'new_zone'))
      : $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', isset($_GET['zID']) ? $Admin->link()->set_parameter('zID', (int)$_GET['zID']) : $Admin->link())
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
