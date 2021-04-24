<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $system_info = new system_info();
  $action = $_GET['action'] ?? '';
  $admin_hooks->cat('preAction');

  switch ($action) {
    case 'save':
      header('Content-type: text/plain');
      header('Content-disposition: attachment; filename=server_info-' . date('YmdHis') . '.txt');
      echo $system_info;
      exit();
  }
  $admin_hooks->cat('postAction');
  Guarantor::ensure_global('Admin');

  require 'includes/template_top.php';
?>

  <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>

<?php
  if ('export' === $action) {
?>
    <div class="alert alert-info">
      <?= TEXT_EXPORT_INTRO ?>
    </div>

    <?php
    echo (new Textarea('server configuration',
      ['cols' => '100', 'rows' => '15']))->set_text("$system_info");

    echo Admin::button(
      BUTTON_SAVE_TO_DISK,
      'fas fa-save',
      'btn-success btn-block btn-lg my-2',
      $Admin->link('server_info.php', ['action' => 'save']));
  } else {
    $server = parse_url(HTTP_SERVER);
?>

  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="thead-dark">
        <tr>
          <th><?= TABLE_HEADING_KEY ?></th>
          <th><?= TABLE_HEADING_VALUE ?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?= TITLE_SERVER_HOST ?></td>
          <td><?= $server['host'] . ' (' . gethostbyname($server['host']) . ')' ?></td>
        </tr>
        <tr>
          <td><?= TITLE_DATABASE_HOST ?></td>
          <td><?= DB_SERVER . ' (' . gethostbyname(DB_SERVER) . ')' ?></td>
        </tr>
        <tr>
          <td><?= TITLE_SERVER_OS ?></td>
          <td><?= $system_info->get('system', 'os') . ' ' . $system_info->get('system', 'kernel') ?></td>
        </tr>
        <tr>
          <td><?= TITLE_DATABASE ?></td>
          <td><?= 'MySQL ' . $system_info->get('mysql', 'version') ?></td>
        </tr>
        <tr>
          <td><?= TITLE_SERVER_DATE ?></td>
          <td><?= $system_info->get('system', 'date') ?></td>
        </tr>
        <tr>
          <td><?= TITLE_DATABASE_DATE ?></td>
          <td><?= $system_info->get('mysql', 'date') ?></td>
        </tr>
        <tr>
          <td><?= TITLE_SERVER_UP_TIME ?></td>
          <td><?= $system_info->get('system', 'uptime') ?></td>
        </tr>
        <tr>
          <td><?= TITLE_HTTP_SERVER ?></td>
          <td><?= $system_info->get('system', 'http_server') ?></td>
        </tr>
        <tr>
          <td><?= TITLE_PHP_VERSION ?></td>
          <td><?= $system_info->get('php', 'version') . ' (' . TITLE_ZEND_VERSION . ' ' . $system_info->get('php', 'zend') . ')' ?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <?php
    echo Admin::button(
      IMAGE_EXPORT, 'fas fa-save', 'btn-danger',
      $Admin->link('server_info.php', ['action' => 'export']));

  }

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
