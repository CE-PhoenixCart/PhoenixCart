<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_gdpr_ip_addresses extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_GDPR_IP_';

    function __construct() {
      parent::__construct(__FILE__);
    }

    function execute() {
      global $port_my_data;

      $ip_address = [];

      $ar_ip_query = $GLOBALS['db']->query("select identifier from action_recorder where user_id = " . (int)$_SESSION['customer_id']);

      if (mysqli_num_rows($ar_ip_query)) {
        while ($ar_ip = $ar_ip_query->fetch_assoc()) {
          if (filter_var($ar_ip['identifier'], FILTER_VALIDATE_IP)) {
            $ip_address[] = $ar_ip['identifier'];
          }
        }
      }

      $ip_address = array_unique($ip_address);

      if (count($ip_address) > 0) {
        $port_my_data['YOU']['IP']['COUNT'] = count($ip_address);

        if ($port_my_data['YOU']['IP']['COUNT'] > 0) {
          $i = 1;
          foreach ($ip_address as $k) {
            $port_my_data['YOU']['IP']['LIST'][$i] = $k;

            $i++;
          }

          $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
          include 'includes/modules/content/cm_template.php';

          // js for delete button
          $delete_action_js = <<<EOD
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.btn-delete-ip').forEach(function(button) {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      
      var iID = button.getAttribute('data-ip-id');
      var parent = button.closest('li');
      var i_num = parseInt(document.querySelector('span.num_ip').innerHTML, 10);
      var i_new_num = i_num - 1;
      
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'ext/scripts/cm_gdpr_update.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
          parent.classList.add('list-group-item-danger');
          fadeOut(parent, 1000);
          document.querySelector('span.num_ip').innerHTML = i_new_num;
        }
      };
      xhr.send('do=ip&ip_id=' + encodeURIComponent(iID));
    });
  });

  function fadeOut(element, duration) {
    var opacity = 1;
    var interval = 50;
    var gap = interval / duration;
    function step() {
      opacity -= gap;
      if (opacity <= 0) {
        opacity = 0;
        element.style.display = 'none';
        clearInterval(fade);
      }
      element.style.opacity = opacity;
    }
    var fade = setInterval(step, interval);
  }
});
</script>
EOD;

          $GLOBALS['Template']->add_block($delete_action_js, 'footer_scripts');
        }
      }
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable IP Address Module',
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
