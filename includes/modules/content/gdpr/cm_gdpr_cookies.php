<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_gdpr_cookies extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_GDPR_COOKIES_';

    function __construct() {
      parent::__construct(__FILE__);
    }

    function execute() {
      global $port_my_data;

      if (count($_COOKIE) > 0) {
        $port_my_data['YOU']['SITE']['COOKIES']['COUNT'] = count($_COOKIE);
        $n = 1;
        foreach ($_COOKIE as $k => $v) {
          $port_my_data['YOU']['SITE']['COOKIES']['LIST'][$n]['NAME'] = $k;
          $port_my_data['YOU']['SITE']['COOKIES']['LIST'][$n]['CONTENT'] = $v;

          $n++;
        }

        $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
        include 'includes/modules/content/cm_template.php';

        // js for delete button
        $delete_button_js = <<<EOD
<script>
document.addEventListener('DOMContentLoaded', function() {
  var deleteButtons = document.querySelectorAll('.btn-delete-cookie');
  
  deleteButtons.forEach(function(button) {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      
      var tr = button.closest('tr');
      var cookie = button.getAttribute('data-cookie-sess');
      
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'ext/scripts/cm_gdpr_update.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');      
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          tr.classList.add('danger', 'text-danger');
          setTimeout(function() {
            tr.style.display = 'none';
          }, 1000);
        }
      };
      
      xhr.send('do=cookies&cookie=' + encodeURIComponent(cookie));
    });
  });
});
</script>
EOD;

        $GLOBALS['Template']->add_block($delete_button_js, 'footer_scripts');
      }
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Cookies Module',
          'value' => 'True',
          'desc' => 'Should this module be shown on the GDPR page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '700',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
