<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_gdpr_reviews extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_GDPR_REVIEWS_';

    function __construct() {
      parent::__construct(__FILE__);
    }

    public function install($parameter_key = null) {
      $structure_query = $GLOBALS['db']->query("SHOW COLUMNS FROM reviews LIKE 'is_anon'");
      if (!mysqli_num_rows($structure_query)) {
        $GLOBALS['db']->query("ALTER TABLE reviews ADD is_anon ENUM('y','n') NOT NULL DEFAULT 'n'");
      }

      parent::install($parameter_key);
    }

    function execute() {
      global $port_my_data;

      $reviews_query = $GLOBALS['db']->query("select r.*, pd.* from reviews r left join products_description pd on r.products_id = pd.products_id where r.customers_id = " . (int)$_SESSION['customer_id'] . " order by reviews_id desc");
      $num_reviews = mysqli_num_rows($reviews_query);

      $port_my_data['YOU']['REVIEW']['COUNT'] = $num_reviews;

      if ($num_reviews) {
        $n = 1;
        while ($reviews = $reviews_query->fetch_assoc()) {
          $port_my_data['YOU']['REVIEW']['LIST'][$n]['ID'] = $reviews['reviews_id'];
          $port_my_data['YOU']['REVIEW']['LIST'][$n]['PRODUCT'] = $reviews['products_name'];
          $port_my_data['YOU']['REVIEW']['LIST'][$n]['DATE'] = $reviews['date_added'];
          $port_my_data['YOU']['REVIEW']['LIST'][$n]['RATING'] = $reviews['reviews_rating'];
          $port_my_data['YOU']['REVIEW']['LIST'][$n]['ANON'] = strtoupper($reviews['is_anon']);

          $n++;
        }

        $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
        include 'includes/modules/content/cm_template.php';

        // js for delete button
        $anonymized_text = MODULE_CONTENT_GDPR_REVIEWS_ANONYMIZED;
        $anonymized_name = MODULE_CONTENT_GDPR_REVIEWS_ANONYMIZED_NAME;

        $delete_button_js = <<<EOD
<script>
document.addEventListener('DOMContentLoaded', function() {
  var buttons = document.querySelectorAll('.btn-update-review');

  buttons.forEach(function(button) {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      
      var rID = this.getAttribute('data-review-id');
      var action = this.getAttribute('id');
      var litem = this.closest('li');
      var r_num = parseInt(document.querySelector('span.num_reviews').innerHTML, 10);
      var r_new_num = r_num - 1;

      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'ext/scripts/cm_gdpr_update.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          if (action === 'delete') {
            litem.classList.add('list-group-item-danger');
            setTimeout(function() {
              litem.style.display = 'none';
            }, 1000);
            document.querySelector('span.num_reviews').innerHTML = r_new_num;
          } else {
            litem.classList.add('list-group-item-info');
            litem.insertAdjacentHTML('beforeend', '{$anonymized_text}');
            document.querySelector('a#anonymize').style.display = 'none';
          }
        }
      };
      xhr.send('do=review&action=' + encodeURIComponent(action) + '&review_id=' + encodeURIComponent(rID) + '&anon=' + encodeURIComponent('{$anonymized_name}'));
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
          'title' => 'Enable Reviews Module',
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
          'value' => '550',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }