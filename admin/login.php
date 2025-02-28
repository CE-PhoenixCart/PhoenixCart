<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $login_request = true;
  $always_valid_actions = ['logoff'];

  require 'includes/application_top.php';

// prepare to log out an active administrator if the login page is accessed again
  if (isset($_SESSION['admin'])) {
    $_GET['action'] = 'logoff';
  }

  require 'includes/segments/process_action.php';

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
    $parameters = ['action' => 'create'];
  } else {
    $button_text = BUTTON_LOGIN;
    $parameters = ['action' => 'process'];
  }

  $input_parameters = [
    'required' => null,
    'autocapitalize' => 'none',
    'aria-required' => 'true',
    'class' => 'form-control text-muted border-0 text-muted',
  ];

  require 'includes/template_top.php';
?>

  <div class="mx-auto col-sm-6 col-md-4 col-lg-3">
    <div class="card text-center shadow my-5">
      <div class="card-header text-white bg-dark"><?= HEADING_TITLE ?></div>
      <div class="px-5 py-2">
        <?= $Admin->image('images/CE-Phoenix.png', ['alt' => 'CE PhoenixCart', 'class' => 'card-img-top']) ?>
      </div>

      <?= new Form('login', $Admin->link('login.php', $parameters)) ?>
        <ul class="list-group list-group-flush">
          <li class="list-group-item border-top"><?= new Input('username', $input_parameters + ['placeholder' => TEXT_USERNAME, 'autocomplete' => 'username', 'autofocus' => 'autofocus']) ?></li>
          <li class="list-group-item"><?= new Input('password', $input_parameters + ['placeholder' => TEXT_PASSWORD, 'autocomplete' => 'current-password'], 'password') ?></li>
          <li class="list-group-item border-bottom-0 d-grid"><?= new Button($button_text, 'fas fa-key', 'btn-success') ?></li>
        </ul>
      </form>

<?php
  if (count($languages) > 1) {
?>
      <div class="card-footer">
        <?=
          (new Form('adminlanguage', $Admin->link('index.php'), 'get'))->hide_session_id(),
          (new Select('language', $languages, ['class' => 'form-select', 'onchange' => 'this.form.submit();']))->set_selection($language_selected),
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
