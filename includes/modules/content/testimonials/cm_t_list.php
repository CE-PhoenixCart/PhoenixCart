<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_t_list extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_TESTIMONIALS_LIST_';

    function __construct() {
      parent::__construct(__FILE__);
    }

    function execute() {
      $testimonials_sql = "SELECT t.*, td.* FROM testimonials t, testimonials_description td WHERE t.testimonials_id = td.testimonials_id";
      if (MODULE_CONTENT_TESTIMONIALS_LIST_ALL != 'All') {
        $testimonials_sql .= " AND td.languages_id = " . (int)$_SESSION['languages_id'];
      }
      $testimonials_sql .= " AND t.testimonials_status = 1 ORDER BY t.testimonials_id DESC";

      $testimonials_split = new splitPageResults($testimonials_sql, MODULE_CONTENT_TESTIMONIALS_LIST_PAGING);

      if ($testimonials_split->number_of_rows > 0) {
        $testimonials_query = $GLOBALS['db']->query($testimonials_split->sql_query);
      }

      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/content/cm_template.php';
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Module',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'ALL' => [
          'title' => 'View Testimonials',
          'value' => 'All',
          'desc' => 'Do you want to show all Testimonials or language specific Testimonials?',
          'set_func' => "Config::select_one(['All', 'Language Specific'], ",
        ],
        $this->config_key_base . 'PAGING' => [
          'title' => 'Number of Testimonials',
          'value' => '12',
          'desc' => 'How many Testimonials to display per page.',
        ],
        $this->config_key_base . 'CONTENT_WIDTH_EACH' => [
          'title' => 'Item Width',
          'value' => 'col-sm-6 mb-2',
          'desc' => 'What container should each Testimonial be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '200',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
