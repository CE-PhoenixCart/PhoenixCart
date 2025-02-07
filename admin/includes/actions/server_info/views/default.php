<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $server = parse_url(HTTP_SERVER);
?>

  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="table-dark">
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
  
  <?= Admin::button(IMAGE_EXPORT, 'fas fa-save', 'btn-danger', $Admin->link('server_info.php', ['action' => 'export'])) ?>