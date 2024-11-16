<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $unwritable_files = Installer::find_unwritable_files(dirname(__FILE__, 4));

  $errors = [];
  $warnings = [];

  if (!extension_loaded('mysqli')) {
    $errors['mysql'] = TEXT_MYSQLI_REQUIRED;
  }

  $php_version_thumb = ICON_THUMB_SUCCESS;

  if (version_compare(PHP_VERSION, PHP_VERSION_MIN, '<')) {
    $errors['php_version'] = sprintf(TEXT_MINIMUM_VERSION, PHP_VERSION_MIN, PHP_VERSION);
    $php_version_thumb = ICON_THUMB_DANGER;
  }

  if (version_compare(PHP_VERSION, PHP_VERSION_MAX, '>=')) {
    $warnings['php_version'] = sprintf(TEXT_MAXIMUM_VERSION, PHP_VERSION_MAX, PHP_VERSION);
    $php_version_thumb = ICON_THUMB_WARNING;
  }

  if ((int)ini_get('allow_url_fopen') == 0) {
    $warnings['allow_url_fopen'] = TEXT_FOPEN_WRAPPERS_REQUIRED;
  }

  if (!extension_loaded('cURL')) {
    $warnings['curl'] = TEXT_CURL_REQUIRED;
  }
  
  if (!extension_loaded('intl')) {
    $warnings['intl'] = TEXT_INTL_PREFERRED;
  }
?>

<div class="alert alert-info" role="alert">
  <h1><?= TEXT_WELCOME_TO ?></h1>

  <?= TEXT_HELPS_YOU_SELL ?>
</div>

<div class="row">
  <div class="col order-last">

    <h2 class="display-4"><?= sprintf(TEXT_NEW_INSTALLATION_OF, Versions::get('Phoenix')) ?></h2>

<?php
  if (!empty($unwritable_files) || !empty($errors) || !empty($warnings)) {
?>

    <table class="table table-condensed table-striped">
<?php
    if (!empty($errors)) {
      foreach ( $errors as $key => $value ) {
        echo '<tr class="table-danger">';
          echo '<th>' . $key . '</th>';
          echo '<td>' . $value . '</td>';
        echo '</tr>';
      }
    }

    if (!empty($warnings)) {
      foreach ( $warnings as $key => $value ) {
        echo '<tr class="table-warning">';
          echo '<th>' . $key . '</th>';
          echo '<td>' . $value . '</td>';
        echo '</tr>';
      }
    }
?>

    </table>

<?php
    if (!empty($unwritable_files)) {
?>

    <div class="alert alert-danger" role="alert">
      <?= TEXT_CONFIGURATION_NOT_WRITABLE ?>
      <ul>
        <li><?= implode("</li>\n<li>", $unwritable_files) ?></li>
      </ul>
    </div>

<?php
    }
  }

  if (!empty($unwritable_files) || !empty($errors)) {
    if (!empty($errors)) {
      echo '<div class="alert alert-info" role="alert"><i>' . TEXT_CHANGE_MAY_NEED_REBOOT . "</i></div>\n";
    }
?>

    <p class="d-grid"><a href="index.php" class="btn btn-danger" role="button"><?= BUTTON_RETRY ?></a></p>

<?php
  } else {
?>

    

    <div id="jsOn" style="display: none;">
      <p class="alert alert-success" role="alert"><?= TEXT_PLEASE_PROCEED ?></p>
      <p class="d-grid"><a href="install.php" class="btn btn-success" role="button"><?= BUTTON_START_INSTALL ?></a></p>
    </div>

    <div id="jsOff">
      <p class="alert alert-danger" role="alert"><?= TEXT_ENABLE_JS ?></p>
      <p class="d-grid"><a href="index.php" class="btn btn-danger" role="button"><?= BUTTON_RETRY ?></a></p>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('jsOff').style.display = 'none';
  document.getElementById('jsOn').style.display = 'block';
});
</script>

<?php
  }
?>

  </div>
  <div class="col-sm-12 col-md-4 order-first">
    <h3><?= TEXT_SERVER_CAPABILITIES ?></h3>

    <table class="table table-condensed table-striped">
      <tr>
        <th colspan="3" class="bg-dark text-white"><?= TEXT_PHP_VERSION ?></th>
      </tr>
      <tr>
        <th><?= implode('.', [PHP_MAJOR_VERSION, PHP_MINOR_VERSION, PHP_RELEASE_VERSION]) ?></th>
        <td colspan="2" class="text-end"><?= $php_version_thumb ?></td>
      </tr>
      <tr>
        <th colspan="3" class="bg-dark text-white"><?= TEXT_PHP_SETTINGS ?></th>
      </tr>
      <tr>
        <th>file_uploads</th>
        <td class="text-end"><?= ((int)ini_get('file_uploads') == 0) ? TEXT_OFF : TEXT_ON ?></td>
        <td class="text-end"><?= ((int)ini_get('file_uploads') == 1) ? ICON_THUMB_SUCCESS : ICON_THUMB_DANGER ?></td>
      </tr>
      <tr>
        <th>auto_start</th>
        <td class="text-end"><?= ((int)ini_get('session.auto_start') == 0) ? TEXT_OFF : TEXT_ON ?></td>
        <td class="text-end"><?= ((int)ini_get('session.auto_start') == 0) ? ICON_THUMB_SUCCESS : ICON_THUMB_DANGER ?></td>
      </tr>
      <tr>
        <th>use_trans_sid</th>
        <td class="text-end"><?= ((int)ini_get('session.use_trans_sid') == 0) ? TEXT_OFF : TEXT_ON ?></td>
        <td class="text-end"><?= ((int)ini_get('session.use_trans_sid') == 0) ? ICON_THUMB_SUCCESS : ICON_THUMB_DANGER ?></td>
      </tr>
      <tr>
        <th colspan="3" class="bg-dark text-white"><?= TEXT_REQUIRED_EXTENSIONS ?></th>
      </tr>
      <tr>
        <th>MySQLi</th>
        <td colspan="2" class="text-end"><?= extension_loaded('mysqli') ? ICON_THUMB_SUCCESS : ICON_THUMB_DANGER ?></td>
      </tr>
      <tr>
        <th>allow_url_fopen</th>
        <td class="text-end"><?= ((int)ini_get('allow_url_fopen') == 0) ? TEXT_OFF : TEXT_ON ?></td>
        <td class="text-end"><?= ((int)ini_get('allow_url_fopen') == 1) ? ICON_THUMB_SUCCESS : ICON_THUMB_DANGER ?></td>
      </tr>
      <tr>
        <th colspan="3" class="bg-dark text-white"><?= TEXT_RECOMMENDED_EXTENSIONS ?></th>
      </tr>
      <tr>
        <th>intl</th>
        <td colspan="2" class="text-end"><?= extension_loaded('intl') ? ICON_THUMB_SUCCESS : ICON_THUMB_WARNING ?></td>
      </tr>
      <tr>
        <th>GD</th>
        <td colspan="2" class="text-end"><?= extension_loaded('gd') ? ICON_THUMB_SUCCESS : ICON_THUMB_WARNING ?></td>
      </tr>
      <tr>
        <th>cURL</th>
        <td colspan="2" class="text-end"><?= extension_loaded('curl') ? ICON_THUMB_SUCCESS : ICON_THUMB_WARNING ?></td>
      </tr>
      <tr>
        <th>OpenSSL</th>
        <td colspan="2" class="text-end"><?= extension_loaded('openssl') ? ICON_THUMB_SUCCESS : ICON_THUMB_WARNING ?></td>
      </tr>
    </table>
  </div>
</div>
