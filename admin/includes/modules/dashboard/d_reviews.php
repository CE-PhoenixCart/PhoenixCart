<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class d_reviews extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ADMIN_DASHBOARD_REVIEWS_';

    public $content_width;

    public function __construct() {
      parent::__construct();

      if ($this->enabled) {
        $this->content_width = $this->base_constant('CONTENT_WIDTH');
      }
    }

    function getOutput() {
      $reviews_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT r.reviews_id, r.date_added, pd.products_name, r.customers_name, r.reviews_rating, r.reviews_status
 FROM reviews r, products_description pd
 WHERE pd.products_id = r.products_id and pd.language_id = %d
 ORDER BY r.date_added DESC
 LIMIT %d
EOSQL
        , (int)$_SESSION['languages_id'], (int)MODULE_ADMIN_DASHBOARD_REVIEWS_DISPLAY));

      $output = '<div class="table-responsive">';
        $output .= '<table class="table table-striped table-hover mb-2">';
          $output .= '<thead class="table-dark">';
            $output .= '<tr>';
              $output .= '<th>' . MODULE_ADMIN_DASHBOARD_REVIEWS_TITLE . '</th>';
              $output .= '<th>' . MODULE_ADMIN_DASHBOARD_REVIEWS_DATE . '</th>';
              $output .= '<th>' . MODULE_ADMIN_DASHBOARD_REVIEWS_REVIEWER . '</th>';
              $output .= '<th>' . MODULE_ADMIN_DASHBOARD_REVIEWS_RATING . '</th>';
              $output .= '<th class="text-end">' . MODULE_ADMIN_DASHBOARD_REVIEWS_REVIEW_STATUS . '</th>';
            $output .= '</tr>';
          $output .= '</thead>';
          $output .= '<tbody>';

          while ($reviews = $reviews_query->fetch_assoc()) {
            $status_icon = ($reviews['reviews_status'] == '1') ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>';
            $output .= '<tr>';
              $output .= '<td><a href="' . $GLOBALS['Admin']->link('reviews.php', ['rID' => (int)$reviews['reviews_id'], 'action' => 'edit']) . '">' . $reviews['products_name'] . '</a></td>';
              $output .= '<td>' . Date::abridge($reviews['date_added']) . '</td>';
              $output .= '<td>' . htmlspecialchars($reviews['customers_name']) . '</td>';
              $output .= '<td>' . new star_rating((float)$reviews['reviews_rating']) . '</td>';
              $output .= '<td class="text-end">' . $status_icon . '</td>';
            $output .= '</tr>';
          }

          $output .= '</tbody>';
        $output .= '</table>';
      $output .= '</div>';

      return $output;
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Reviews Module',
          'value' => 'True',
          'desc' => 'Do you want to show the latest reviews on the dashboard?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'DISPLAY' => [
          'title' => 'Reviews to display',
          'value' => '5',
          'desc' => 'This number of Reviews will display, ordered by latest added.',
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-md-6 mb-2',
          'desc' => 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '800',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }
  }
