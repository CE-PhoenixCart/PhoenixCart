<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $is_iis = stripos($_SERVER['SERVER_SOFTWARE'], 'iis');
  Guarantor::ensure_global('Admin');

  $htaccess_path = DIR_FS_ADMIN . '.htaccess';
  $htpasswd_path = DIR_FS_ADMIN . '.htpasswd_phoenix';
  $authuserfile_lines = [
    '##### Phoenix ADMIN PROTECTION - BEGIN #####',
    'AuthType Basic',
    'AuthName "CE Phoenix Administration Tool"',
    "AuthUserFile $htpasswd_path",
    'Require valid-user',
    '##### Phoenix ADMIN PROTECTION - END #####',
  ];

  $htaccess_lines = [];
  if (!$is_iis && file_exists($htpasswd_path) && Path::is_writable($htpasswd_path) && file_exists($htaccess_path) && Path::is_writable($htaccess_path)) {
    if (filesize($htaccess_path) > 0) {
      $htaccess_lines = explode("\n", file_get_contents($htaccess_path));
    }

    $htpasswd_lines = (filesize($htpasswd_path) > 0) ? explode("\n", file_get_contents($htpasswd_path)) : [];
  } else {
    $htpasswd_lines = false;
  }

  require 'includes/segments/process_action.php';

  $secMessageStack = new messageStack();

  $apache_users = [];
  if (is_array($htpasswd_lines)) {
    if (empty($htpasswd_lines)) {
      $secMessageStack->add(sprintf(HTPASSWD_INFO, $htaccess_path, implode('<br>', $authuserfile_lines), $htpasswd_path), 'error');
    } else {
      $secMessageStack->add(HTPASSWD_SECURED, 'success');

      foreach ($htpasswd_lines as $htpasswd_line) {
        $end = strpos($htpasswd_line, ':');
        if (false !== $end) {
          $apache_users[] = substr($htpasswd_line, 0, $end);
        }
      }
    }
  } else if (!$is_iis) {
    $secMessageStack->add(sprintf(HTPASSWD_PERMISSIONS, $htaccess_path, $htpasswd_path), 'error');
  }

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col text-right align-self-center">
      <?=
        empty($action)
      ? new Button(IMAGE_INSERT_NEW_ADMIN, 'fas fa-users', 'btn-danger', [], $Admin->link('administrators.php', ['action' => 'new']))
      : new Button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', [], $Admin->link('administrators.php'))
      ?>
    </div>
  </div>

  <div class="row no-gutters">
    <div class="col-12 col-sm-8">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
              <th><?= TABLE_HEADING_ADMINISTRATORS ?></th>
              <th class="text-center"><?= TABLE_HEADING_HTPASSWD ?></th>
              <th class="text-right"><?= TABLE_HEADING_ACTION ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $admins_query = $db->query("SELECT id, user_name FROM administrators ORDER BY user_name");
            while ($administrator = $admins_query->fetch_assoc()) {
              if (!isset($aInfo) && (!isset($_GET['aID']) || ($_GET['aID'] == $administrator['id'])) && (substr($action, 0, 3) != 'new')) {
                $aInfo = new objectInfo($administrator);
              }

              if ($is_iis) {
                $htpasswd_secured = TEXT_HTPASSWRD_NA_IIS;
              } elseif (in_array($administrator['user_name'], $apache_users)) {
                $htpasswd_secured = '<i class="fas fa-check-circle text-success"></i>';
              } else {
                $htpasswd_secured = '<i class="fas fa-times-circle text-danger"></i>';
              }

              if ( isset($aInfo->id) && ($administrator['id'] == $aInfo->id) ) {
                echo '<tr class="table-active" onclick="document.location.href=\'' . $Admin->link('administrators.php', ['aID' => $aInfo->id, 'action' => 'edit']) . '\'">' . PHP_EOL;
                $icon = '<i class="fas fa-chevron-circle-right text-info"></i>';
              } else {
                echo '<tr onclick="document.location.href=\'' . $Admin->link('administrators.php', ['aID' => $administrator['id']]) . '\'">' . PHP_EOL;
                $icon = '<a href="' . $Admin->link('administrators.php', ['aID' => $administrator['id']]) . '"><i class="fas fa-info-circle text-muted"></i></a>';
              }
              ?>
                <td><?= $administrator['user_name'] ?></td>
                <td class="text-center"><?= $htpasswd_secured ?></td>
                <td class="text-right"><?= $icon ?></td>
              </tr>
<?php
  }
?>
          </tbody>
        </table>
      </div>

      <?= $secMessageStack->output() ?>

    </div>

<?php
  if ($action_file = $Admin->locate('/infoboxes', $action)) {
    require DIR_FS_ADMIN . 'includes/components/infobox.php';
  }
?>

  </div>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
