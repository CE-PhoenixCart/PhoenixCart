<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

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
  if (!$is_iis && file_exists($htpasswd_path) && tep_is_writable($htpasswd_path) && file_exists($htaccess_path) && tep_is_writable($htaccess_path)) {
    if (filesize($htaccess_path) > 0) {
      $htaccess_lines = explode("\n", file_get_contents($htaccess_path));
    }

    $htpasswd_lines = (filesize($htpasswd_path) > 0) ? explode("\n", file_get_contents($htpasswd_path)) : [];
  } else {
    $htpasswd_lines = false;
  }

  $action = $_GET['action'] ?? '';

  $admin_hooks->cat('preAction');

  if (!Text::is_empty($action)) {
    switch ($action) {
      case 'insert':
        $username = Text::input($_POST['username']);
        $password = Text::input($_POST['password']);

        $check_query = $db->query("SELECT id FROM administrators WHERE user_name = '" . $db->escape($username) . "' LIMIT 1");

        if (mysqli_num_rows($check_query) < 1) {
          $db->query("INSERT INTO administrators (user_name, user_password) VALUES ('" . $db->escape($username) . "', '" . $db->escape(Password::hash($password)) . "')");

          if (is_array($htpasswd_lines)) {
            foreach ($htpasswd_lines as $i => $htpasswd_line) {
              list($ht_username, $ht_password) = explode(':', $htpasswd_line, 2);

              if ($ht_username == $username) {
                unset($htpasswd_lines[$i]);
              }
            }

            if (isset($_POST['htaccess']) && ($_POST['htaccess'] === 'true')) {
              $htpasswd_lines[] = $username . ':' . apache_password::hash($password);
            }

            file_put_contents($htpasswd_path, implode("\n", $htpasswd_lines));

            if (empty($htpasswd_lines)) {
              $htaccess_lines = array_diff($htaccess_lines, $authuserfile_lines);
            } elseif (!in_array('AuthUserFile ' . DIR_FS_ADMIN . '.htpasswd_phoenix', $htaccess_lines)) {
              array_splice($htaccess_lines, count($htaccess_lines), 0, $authuserfile_lines);
            }

            file_put_contents($htaccess_path, implode("\n", $htaccess_lines));
          }
        } else {
          $messageStack->add_session(ERROR_ADMINISTRATOR_EXISTS, 'error');
        }

        $admin_hooks->cat('insertAction');

        Href::redirect($Admin->link('administrators.php'));
        break;
      case 'save':
        $username = Text::input($_POST['username']);
        $password = Text::input($_POST['password']);

        $check_query = $db->query("SELECT id, user_name FROM administrators WHERE id = " . (int)$_GET['aID']);
        $check = $check_query->fetch_assoc();

// update username in current session if changed
        if ( ($check['id'] == $admin['id']) && ($check['user_name'] != $admin['username']) ) {
          $admin['username'] = $username;
        }

// update username in htpasswd if changed
        if (is_array($htpasswd_lines)) {
          foreach ($htpasswd_lines as $i => $htpasswd_line) {
            if (false === strpos($htpasswd_line, ':')) {
              continue;
            }

            list($ht_username, $ht_password) = explode(':', $htpasswd_line, 2);

            if ( ($check['user_name'] == $ht_username) && ($check['user_name'] !== $username) ) {
              $htpasswd_lines[$i] = $username . ':' . $ht_password;
            }
          }
        }

        $db->query("UPDATE administrators SET user_name = '" . $db->escape($username) . "' WHERE id = " . (int)$_GET['aID']);

        if (!Text::is_empty($password)) {
// update password in htpasswd
          if (is_array($htpasswd_lines)) {
            foreach ($htpasswd_lines as $i => $htpasswd_line) {
              list($ht_username, $ht_password) = explode(':', $htpasswd_line, 2);

              if ($ht_username == $username) {
                unset($htpasswd_lines[$i]);
              }
            }

            if (isset($_POST['htaccess']) && ($_POST['htaccess'] === 'true')) {
              $htpasswd_lines[] = $username . ':' . apache_password::hash($password);
            }
          }

          $db->query("UPDATE administrators SET user_password = '" . $db->escape(Password::hash($password)) . "' WHERE id = " . (int)$_GET['aID']);
        } elseif (!isset($_POST['htaccess']) || ($_POST['htaccess'] !== 'true')) {
          if (is_array($htpasswd_lines)) {
            foreach ($htpasswd_lines as $i => $htpasswd_line) {
              list($ht_username, $ht_password) = explode(':', $htpasswd_line, 2);

              if ($ht_username == $username) {
                unset($htpasswd_lines[$i]);
              }
            }
          }
        }

// write new htpasswd file
        if (is_array($htpasswd_lines)) {
          file_put_contents($htpasswd_path, implode("\n", $htpasswd_lines));

          if (empty($htpasswd_lines)) {
            $htaccess_lines = array_diff($htaccess_lines, $authuserfile_lines);
          } elseif (!in_array('AuthUserFile ' . DIR_FS_ADMIN . '.htpasswd_phoenix', $htaccess_lines)) {
            array_splice($htaccess_lines, count($htaccess_lines), 0, $authuserfile_lines);
          }

          file_put_contents($htaccess_path, implode("\n", $htaccess_lines));
        }

        $admin_hooks->cat('saveAction');

        Href::redirect($Admin->link('administrators.php', 'aID=' . (int)$_GET['aID']));
        break;
      case 'deleteconfirm':
        $id = Text::input($_GET['aID']);

        $check_query = $db->query("SELECT id, user_name FROM administrators WHERE id = " . (int)$id);
        $check = $check_query->fetch_assoc();

        if ($admin['id'] == $check['id']) {
          unset($_SESSION['admin']);
        }

        $db->query("DELETE FROM administrators WHERE id = " . (int)$id);

        if (is_array($htpasswd_lines)) {
          foreach ($htpasswd_lines as $i => $htpasswd_line) {
            list($ht_username, $ht_password) = explode(':', $htpasswd_line, 2);

            if ($ht_username == $check['user_name']) {
              unset($htpasswd_lines[$i]);
            }
          }

          file_put_contents($htpasswd_path, implode("\n", $htpasswd_lines));

          if (empty($htpasswd_lines)) {
            file_put_contents($htaccess_path, implode("\n",
              array_diff($htaccess_lines, $authuserfile_lines)) . "\n");
          }
        }

        $admin_hooks->cat('deleteConfirmAction');

        Href::redirect($Admin->link('administrators.php'));
        break;
    }
  }

  $admin_hooks->cat('postAction');

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
  $heading = [];
  $contents = [];

  switch ($action) {
    case 'new':
      $heading[] = ['text' => TEXT_INFO_HEADING_NEW_ADMINISTRATOR];

      $contents = ['form' => new Form('administrator', $Admin->link('administrators.php', ['action' => 'insert']), 'post', ['autocomplete' => 'off'])];
      $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];
      $contents[] = ['text' => TEXT_INFO_USERNAME . new Input('username', ['required' => null, 'autocapitalize' => 'none', 'aria-required' => 'true'])];
      $contents[] = ['text' => TEXT_INFO_PASSWORD . new Input('password', ['required' => null, 'autocapitalize' => 'none', 'aria-required' => 'true'], 'password')];

      if (is_array($htpasswd_lines)) {
        $contents[] = [
          'text' => '<div class="custom-control custom-switch">'
                  . new Tickable('htaccess', ['class' => 'custom-control-input', 'id' => 'aHtpasswd', 'value' => 'true'], 'checkbox')
                  . '<label for="aHtpasswd" class="custom-control-label text-muted"><small>' . TEXT_INFO_PROTECT_WITH_HTPASSWD . '</small></label></div>'];
      }

      $contents[] = [
        'class' => 'text-center',
        'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success mr-2')
                . new Button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', [], $Admin->link('administrators.php')),
      ];
      break;
    case 'edit':
      $heading[] = ['text' => $aInfo->user_name];

      $contents = ['form' => new Form('administrator', $Admin->link('administrators.php', ['aID' => $aInfo->id, 'action' => 'save']), 'post', ['autocomplete' => 'off'])];
      $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];
      $contents[] = ['text' => TEXT_INFO_USERNAME . new Input('username', ['value' => $aInfo->user_name, 'required' => null, 'autocapitalize' => 'none', 'aria-required' => 'true'])];
      $contents[] = ['text' => TEXT_INFO_NEW_PASSWORD . new Input('password', ['required' => null, 'autocapitalize' => 'none', 'aria-required' => 'true'], 'password')];

      if (is_array($htpasswd_lines)) {
        $checkbox = new Tickable('htaccess', ['class' => 'custom-control-input', 'id' => 'aHtpasswd', 'value' => 'true'], 'checkbox');

        foreach ($htpasswd_lines as $htpasswd_line) {
          list($ht_username, $ht_password) = explode(':', $htpasswd_line, 2);

          if ($ht_username == $aInfo->user_name) {
            $checkbox->tick();
            break;
          }
        }

        $contents[] = [
          'text' => '<div class="custom-control custom-switch">' . $checkbox
                  . '<label for="aHtpasswd" class="custom-control-label text-muted"><small>' . TEXT_INFO_PROTECT_WITH_HTPASSWD . '</small></label></div>',
        ];
      }

      $contents[] = [
        'class' => 'text-center',
        'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success mr-2')
                . new Button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', [], $Admin->link('administrators.php', ['aID' => $aInfo->id])),
      ];
      break;
    case 'delete':
      $heading[] = ['text' => $aInfo->user_name];

      $contents = ['form' => new Form('administrator', $Admin->link('administrators.php', ['aID' => $aInfo->id, 'action' => 'deleteconfirm']))];
      $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
      $contents[] = ['class' => 'text-center text-uppercase font-weight-bold', 'text' => $aInfo->user_name];
      $contents[] = [
        'class' => 'text-center',
        'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger mr-2')
                . new Button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', [], $Admin->link('administrators.php', ['aID' => $aInfo->id])),
      ];
      break;
    default:
      if (isset($aInfo) && is_object($aInfo)) {
        $heading[] = ['text' => $aInfo->user_name];

        $contents[] = [
          'class' => 'text-center',
          'text' => new Button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning mr-2', [], $Admin->link('administrators.php', ['aID' => $aInfo->id, 'action' => 'edit']))
                  . new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', [], $Admin->link('administrators.php', ['aID' => $aInfo->id, 'action' => 'delete'])),
        ];
      }
      break;
  }

  if ( ([] !== $heading) && ([] !== $contents) ) {
    echo '<div class="col-12 col-sm-4">';
      $box = new box();
      echo $box->infoBox($heading, $contents);
    echo '</div>';
  }
?>

  </div>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
