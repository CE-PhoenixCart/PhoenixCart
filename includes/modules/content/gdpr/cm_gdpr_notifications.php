<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_gdpr_notifications extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_GDPR_NOTIFICATIONS_';

    function __construct() {
      parent::__construct(__FILE__);
    }

    function execute() {
      global $port_my_data;

      $notifications_query = $GLOBALS['db']->query("select pn.*, pd.* from products_notifications pn left join products_description pd on pn.products_id = pd.products_id where pn.customers_id = " . (int)$_SESSION['customer_id'] . " and pd.language_id = " . (int)$_SESSION['languages_id'] . " order by pd.products_name");
      $num_notifications = mysqli_num_rows($notifications_query);

      $port_my_data['YOU']['NOTIFICATION']['COUNT'] = $num_notifications;

      if ($num_notifications > 0) {
        $n = 1;

        while ($notifications = $notifications_query->fetch_assoc()) {
          $port_my_data['YOU']['NOTIFICATION']['LIST'][$n]['PRODUCT'] = $notifications['products_name'];
          $port_my_data['YOU']['NOTIFICATION']['LIST'][$n]['DATE'] = $notifications['date_added'];
          $port_my_data['YOU']['NOTIFICATION']['LIST'][$n]['PID'] = (int)$notifications['products_id'];
          $n++;
        }

        $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
        include 'includes/modules/content/cm_template.php';

        // js for delete button
        $delete_action_js = <<<EOD
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.btn-delete-notification').forEach(function(button) {
    button.addEventListener('click', function(e) {
      e.preventDefault();
            
      var nID = this.getAttribute('data-notification-id');
      var parent = this.closest('li');
      var n_num = parseInt(document.querySelector('span.num_notifications').innerHTML, 10);
      var n_new_num = n_num - 1;

      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'ext/scripts/cm_gdpr_update.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            parent.classList.add('list-group-item-danger');
            parent.style.transition = 'opacity 1s'; // Add fade out effect
            parent.style.opacity = '0';
            document.querySelector('span.num_notifications').innerHTML = n_new_num;
        }
      };
      xhr.send('do=notification&notification_id=' + encodeURIComponent(nID));
    });
  });
});
</script>
EOD;

        $GLOBALS['Template']->add_block($delete_action_js, 'footer_scripts');
      }
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Notifications Module',
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
          'value' => '750',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
