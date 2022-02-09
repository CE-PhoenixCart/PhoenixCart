<div class="cm-paypal-login <?= (OSCOM_APP_PAYPAL_LOGIN_CONTENT_WIDTH == 'Half') ? 'col-sm-6' : 'col-sm-12' ?>">
  <div class="card mb-2">
    <div class="card-header">
      <?= $cm_paypal_login->_app->getDef('module_login_template_title') ?>
    </div>
    <div class="card-body">

<?php
  if ( OSCOM_APP_PAYPAL_LOGIN_STATUS == '0' ) {
    echo '<p class="alert alert-warning" role="alert">' . $cm_paypal_login->_app->getDef('module_login_template_sandbox_alert') . '</p>';
  }
?>

      <p><?= $cm_paypal_login->_app->getDef('module_login_template_content') ?></p>

      <div id="PayPalLoginButton" class="text-right"></div>

    </div>
  </div>
</div>

<script src="https://www.paypalobjects.com/js/external/api.js"></script>
<script>
paypal.use( ["login"], function(login) {
  login.render ({

<?php
  if ( OSCOM_APP_PAYPAL_LOGIN_STATUS == '0' ) {
    echo '    "authend": "sandbox",';
  }

  if ( OSCOM_APP_PAYPAL_LOGIN_THEME == 'Neutral' ) {
    echo '    "theme": "neutral",';
  }
?>

    "locale": "<?= $cm_paypal_login->_app->getDef('module_login_language_locale') ?>",
    "appid": "<?= (OSCOM_APP_PAYPAL_LOGIN_STATUS == '1') ? OSCOM_APP_PAYPAL_LOGIN_LIVE_CLIENT_ID : OSCOM_APP_PAYPAL_LOGIN_SANDBOX_CLIENT_ID ?>",
    "scopes": "<?= implode(' ', $use_scopes) ?>",
    "containerid": "PayPalLoginButton",
    "returnurl": "<?= str_replace('&amp;', '&', $GLOBALS['Linker']->build('login.php', ['action' => 'paypal_login'], false)) ?>"
  });
});
</script>
<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
