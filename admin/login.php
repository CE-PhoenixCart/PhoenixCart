<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $login_request = true;

  require 'includes/application_top.php';

  Guarantor::ensure_global('Admin');
  $action = $_GET['action'] ?? '';

  $admin_hooks->cat('preAction');

// prepare to log out an active administrator if the login page is accessed again
  if (isset($_SESSION['admin'])) {
    $action = 'logoff';
  }

  if (!Text::is_empty($action)) {
    switch ($action) {
      case 'process':
        if (isset($_SESSION['redirect_origin']['auth_user']) && !isset($_POST['username'])) {
          $username = Text::input($_SESSION['redirect_origin']['auth_user']);
          $password = Text::input($_SESSION['redirect_origin']['auth_pw']);
        } else {
          $username = Text::input($_POST['username']);
          $password = Text::input($_POST['password']);
        }

        $actionRecorder = new actionRecorderAdmin('ar_admin_login', null, $username);

        if ($actionRecorder->canPerform()) {
          $check_query = $db->query("SELECT id, user_name, user_password FROM administrators WHERE user_name = '" . $db->escape($username) . "'");

          if (mysqli_num_rows($check_query) === 1) {
            $check = $check_query->fetch_assoc();

            if (Password::validate($password, $check['user_password'])) {
// migrate password if using an older hashing method
              if (Password::needs_rehash($check['user_password'])) {
                $db->query("UPDATE administrators SET user_password = '" . Password::hash($password) . "' WHERE id = " . (int)$check['id']);
              }

              $_SESSION['admin'] = [
                'id' => $check['id'],
                'username' => $check['user_name'],
              ];

              $actionRecorder->_user_id = $_SESSION['admin']['id'];
              $actionRecorder->record();

              $admin_hooks->cat('processActionSuccess');

              if (isset($_SESSION['redirect_origin'])) {
                $link = $Admin->link($_SESSION['redirect_origin']['page'], $_SESSION['redirect_origin']['get']);
                unset($_SESSION['redirect_origin']);

                Href::redirect($link);
              } else {
                Href::redirect($Admin->link('index.php'));
              }
            }

            $admin_hooks->cat('processActionFail');
          }

          if (isset($_POST['username'])) {
            $messageStack->add(ERROR_INVALID_ADMINISTRATOR, 'error');
          }
        } else {
          $messageStack->add(sprintf(ERROR_ACTION_RECORDER, (defined('MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES') ? (int)MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES : 5)));
        }

        if (isset($_POST['username'])) {
          $actionRecorder->record(false);
        }

        $admin_hooks->cat('processAction');

        break;

      case 'logoff':
        unset($_SESSION['admin']);

        if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])) {
          $_SESSION['auth_ignore'] = true;
        }

        $admin_hooks->cat('logoffAction');

        Href::redirect($Admin->link('index.php'));

      case 'create':
        $check_query = $db->query("SELECT id FROM administrators LIMIT 1");

        if (mysqli_num_rows($check_query) === 0) {
          $username = Text::input($_POST['username']);
          $password = Text::input($_POST['password']);

          if ( $username ) {
            $db->query("INSERT INTO administrators (user_name, user_password) VALUES ('" . $db->escape($username) . "', '" . $db->escape(Password::hash($password)) . "')");
          }
        }

        $admin_hooks->cat('createAction');

        Href::redirect($Admin->link('login.php'));
    }
  }

  $admin_hooks->cat('postAction');

  $languages = [];
  $language_selected = DEFAULT_LANGUAGE;
  foreach (language::load_all() as $l) {
    $languages[] = [
      'id' => $l['code'],
      'text' => $l['name'],
    ];

    if ($l['directory'] === $_SESSION['language']) {
      $language_selected = $l['code'];
    }
  }

  if (mysqli_num_rows($db->query("SELECT id FROM administrators LIMIT 1")) < 1) {
    $messageStack->add(TEXT_CREATE_FIRST_ADMINISTRATOR, 'warning');
    $button_text = BUTTON_CREATE_ADMINISTRATOR;
    $intro_text = TEXT_CREATE_FIRST_ADMINISTRATOR;
    $parameters = ['action' => 'create'];
  } else {
    $button_text = BUTTON_LOGIN;
    $intro_text = '';
    $parameters = ['action' => 'process'];
  }

  require 'includes/template_top.php';
?>

  <div class="mx-auto w-75 w-md-25">
    <div class="card text-center shadow mt-5">
      <div class="card-header text-white bg-dark"><?= HEADING_TITLE ?></div>
      <div class="px-5 py-2">
        <?= $Admin->image('images/CE-Phoenix.png', ['alt' => 'CE PhoenixCart', 'class' => 'card-img-top']) ?>
      </div>

      <?= new Form('login', $Admin->link('login.php', $parameters)) ?>
        <ul class="list-group list-group-flush">
          <li class="list-group-item border-top"><?= new Input('username', ['required' => null, 'autocapitalize' => 'none', 'aria-required' => 'true', 'placeholder' => TEXT_USERNAME, 'class' => 'form-control text-muted border-0 text-muted']) ?></li>
          <li class="list-group-item"><?= new Input('password', ['required' => null, 'autocapitalize' => 'none', 'aria-required' => 'true', 'placeholder' => TEXT_PASSWORD, 'class' => 'form-control text-muted border-0 text-muted'], 'password') ?></li>
          <li class="list-group-item border-bottom-0"><?= new Button($button_text, 'fas fa-key', 'btn-success btn-block') ?></li>
        </ul>
      </form>

<?php
  echo $intro_text;
  if (count($languages) > 1) {
?>
      <div class="card-footer">
        <?=
          (new Form('adminlanguage', $Admin->link('index.php'), 'get'))->hide_session_id(),
          new Select('language', $languages, $language_selected, ['onchange' => 'this.form.submit();']),
          '</form>'
        ?>
      </div>
<?php
  }
?>
    </div>
  </div>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
