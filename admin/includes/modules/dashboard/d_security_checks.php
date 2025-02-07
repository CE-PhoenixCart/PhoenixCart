<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class d_security_checks extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_';

    public $content_width;

    public function __construct() {
      parent::__construct();

      if ($this->enabled) {
        $this->content_width = $this->base_constant('CONTENT_WIDTH');
      }
    }

    public function getOutput() {
      $messages = [];

      $security_checks = new security_checks();

      foreach (array_column(iterator_to_array($security_checks->generate_modules()), 'class') as $module) {
        if ( !$GLOBALS[$module]->pass() ) {
          Guarantor::guarantee_subarray($messages, $GLOBALS[$module]->type);
          $messages[$GLOBALS[$module]->type][] = $GLOBALS[$module]->get_message();
        }
      }

      if (empty($messages)) {
        return sprintf(<<<'EOHTML'
          <ul class="list-group">
            <li class="list-group-item list-group-item-success d-flex align-items-center">
              <i class="fas fa-5x fa-thumbs-up me-2"></i>
              %s
            </li>
          </ul>
EOHTML
        , MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_SUCCESS);
      }

      $output = '<ul class="list-group">';
        foreach ($messages['error'] ?? [] as $error) {
          $output .= '<li class="list-group-item list-group-item-danger d-flex align-items-center">';
            $output .= '<i class="fas fa-5x fa-times-circle me-2 text-danger"></i>';
            $output .= $error;
          $output .= '</li>';
        }
        foreach ($messages['warning'] ?? [] as $warning) {
          $output .= '<li class="list-group-item list-group-item-warning d-flex align-items-center">';
            $output .= '<i class="fas fa-5x fa-times-circle me-2 text-warning"></i>';
            $output .= $warning;
          $output .= '</li>';
        }
        foreach ($messages['info'] ?? [] as $info) {
          $output .= '<li class="list-group-item list-group-item-info d-flex align-items-center">';
            $output .= '<i class="fas fa-5x fa-exclamation-circle me-2 text-info"></i>';
            $output .= $info;
          $output .= '</li>';
        }

      $output .= '</ul>';

      return $output;
    }

    protected function get_parameters() {
      return [
        'MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_STATUS' => [
          'title' => 'Enable Security Checks Module',
          'value' => 'True',
          'desc' => 'Do you want to run the security checks for this installation?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-md-6 mb-2',
          'desc' => 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).',
        ],
        'MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '600',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
