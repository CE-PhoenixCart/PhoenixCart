<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if ( !class_exists('OSCOM_PayPal') ) {
    include DIR_FS_CATALOG . 'includes/apps/paypal/OSCOM_PayPal.php';
  }

  class d_paypal_app extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ADMIN_DASHBOARD_PAYPAL_APP_';

    public $content_width = 6;
    protected $_app;

    public function __construct() {
      parent::__construct();

      $this->_app = new OSCOM_PayPal();
      $this->_app->loadLanguageFile('admin/balance.php');
      $this->_app->loadLanguageFile('admin/modules/dashboard/d_paypal_app.php');

      $this->title = $this->_app->getDef('module_admin_dashboard_title');
      $this->description = $this->_app->getDef('module_admin_dashboard_description');

      $this->status_key = 'MODULE_ADMIN_DASHBOARD_PAYPAL_APP_SORT_ORDER';
      if ( defined('MODULE_ADMIN_DASHBOARD_PAYPAL_APP_SORT_ORDER') ) {
        $this->enabled = true;
        $this->content_width = (int)($this->base_constant('CONTENT_WIDTH') ?? 6);
      }
    }

    public function getOutput() {
      $Admin =& Guarantor::ensure_global('Admin');

      $version = $this->_app->getVersion();
      $version_check_result = defined('OSCOM_APP_PAYPAL_VERSION_CHECK') ? '"' . OSCOM_APP_PAYPAL_VERSION_CHECK . '"' : 'undefined';
      $can_apply_online_updates = 'false';
      $has_live_account = ($this->_app->hasApiCredentials('live') === true) ? 'true' : 'false';
      $has_sandbox_account = ($this->_app->hasApiCredentials('sandbox') === true) ? 'true' : 'false';
      $version_check_url = $Admin->link('paypal.php', ['action' => 'checkVersion']);
      $new_update_notice = $this->_app->getDef('update_available_body', [
        'button_view_update' => $this->_app->drawButton(
          $this->_app->getDef('button_view_update'),
          $Admin->link('paypal.php', 'action=update'),
          'success', null, true)]);
      $heading_live_account = $this->_app->getDef('heading_live_account',
        ['account' => str_replace('_api1.', '@', $this->_app->getApiCredentials('live', 'username'))]);
      $heading_sandbox_account = $this->_app->getDef('heading_sandbox_account',
        ['account' => str_replace('_api1.', '@', $this->_app->getApiCredentials('sandbox', 'username'))]);
      $receiving_balance_progress = $this->_app->getDef('retrieving_balance_progress');
      $app_get_started = $this->_app->drawButton($this->_app->getDef('button_app_get_started'),
        $Admin->link('paypal.php'), 'warning', null, true);
      $error_balance_retrieval = addslashes($this->_app->getDef('error_balance_retrieval'));
      $get_balance_url = $Admin->link('paypal.php', [
        'action' => 'balance',
        'subaction' => 'retrieve',
        'type' => 'PPTYPE',
      ])->set_separator_encoding(false);

      $output = <<<"EOJS"
<script>
if ( typeof jQuery == 'undefined' ) {
  document.write('<scr' + 'ipt src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></scr' + 'ipt>');
}
</script>
<script>
var OSCOM = {
  dateNow: new Date(),
  htmlSpecialChars: function(string) {
    if ( string == null ) {
      string = '';
    }

    return $('<span />').text(string).html();
  },
  nl2br: function(string) {
    return string.replace(/\\n/g, '<br />');
  },
  APP: {
    PAYPAL: {
      version: '{$version}',
      versionCheckResult: {$version_check_result},
      doOnlineVersionCheck: false,
      canApplyOnlineUpdates: false,
      accountTypes: {
        live: {$has_live_account},
        sandbox: {$has_sandbox_account}
      }
    }
  }
};
</script>

<div class="pp-container">
  <div class="card" id="ppAccountBalanceLive">
    <div class="card-header">
      {$heading_live_account}
    </div>
    <div class="card-body">
      <div id="ppBalanceLiveInfo">
        <p>{$receiving_balance_progress}</p>
      </div>
    </div>
  </div>

  <div class="card" id="ppAccountBalanceSandbox">
    <div class="card-header">
      {$heading_sandbox_account}
    </div>
    <div class="card-body">
      <div id="ppBalanceLiveInfo">
        <p>{$receiving_balance_progress}</p>
      </div>
    </div>
  </div>

  <div class="card" id="ppAccountBalanceNone" style="display: none;">
    <div class="card-body">
      <p>{$app_get_started}</p>
    </div>
  </div>

</div>

<script>
OSCOM.APP.PAYPAL.getBalance = function(type) {
  var def = {
    'error_balance_retrieval': '{$error_balance_retrieval}'
  };

  var divId = 'ppBalance' + type.charAt(0).toUpperCase() + type.slice(1) + 'Info';

  $.get('{$get_balance_url}'.replace('PPTYPE', type), function (data) {
    var balance = {};

    $('#' + divId).empty();

    try {
      data = $.parseJSON(data);
    } catch (ex) {
    }

    if ( (typeof data == 'object') && ('rpcStatus' in data) && (data['rpcStatus'] == 1) ) {
      if ( ('balance' in data) && (typeof data['balance'] == 'object') ) {
        balance = data['balance'];
      }
    } else if ( (typeof data == 'string') && (data.indexOf('rpcStatus') > -1) ) {
      var result = data.split("\\n", 1);

      if ( result.length == 1 ) {
        var rpcStatus = result[0].split('=', 2);

        if ( rpcStatus[1] == 1 ) {
          var entries = data.split("\\n");

          for ( var i = 0; i < entries.length; i++ ) {
            var entry = entries[i].split('=', 2);

            if ( (entry.length == 2) && (entry[0] != 'rpcStatus') ) {
              balance[entry[0]] = entry[1];
            }
          }
        }
      }
    }

    var pass = false;

    for ( var key in balance ) {
      pass = true;

      $('#' + divId).append('<p><strong>' + OSCOM.htmlSpecialChars(key) + ':</strong> ' + OSCOM.htmlSpecialChars(balance[key]) + '</p>');
    }

    if ( pass == false ) {
      $('#' + divId).append('<p>' + def['error_balance_retrieval'] + '</p>');
    }
  }).fail(function() {
    $('#' + divId).empty().append('<p>' + def['error_balance_retrieval'] + '</p>');
  });
};

$(function() {
  (function() {
    var pass = false;

    if ( OSCOM.APP.PAYPAL.accountTypes['live'] == true ) {
      pass = true;

      $('#ppAccountBalanceSandbox').hide();

      OSCOM.APP.PAYPAL.getBalance('live');
    } else {
      $('#ppAccountBalanceLive').hide();

      if ( OSCOM.APP.PAYPAL.accountTypes['sandbox'] == true ) {
        pass = true;

        OSCOM.APP.PAYPAL.getBalance('sandbox');
      } else {
        $('#ppAccountBalanceSandbox').hide();
      }
    }

    if ( pass == false ) {
      $('#ppAccountBalanceNone').show();
    }
  })();
});
</script>

EOJS;

      return $output;
    }

    protected function get_parameters() {
      return [
        'MODULE_ADMIN_DASHBOARD_PAYPAL_APP_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '6',
          'desc' => 'What width container should the content be shown in? (12 = full width, 6 = half width).',
          'set_func' => "tep_cfg_select_option(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_PAYPAL_APP_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '1300',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
