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

    public $content_width = 6;

    public function __construct() {
      parent::__construct();

      if ($this->enabled) {
        $this->content_width = (int)($this->base_constant('CONTENT_WIDTH') ?? 6);
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
          <div class="alert alert-success">
            <div class="clearfix"><i class="fas fa-5x fa-thumbs-up float-left mr-2"></i><strong>%s</strong></div>
          </div>
EOHTML
        , MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_SUCCESS);
      }

      $output = '';
      if (isset($messages['error'])) {
        $output .= '<div class="alert alert-danger">';
        foreach ($messages['error'] as $error) {
          $output .= '<div class="clearfix"><i class="fas fa-5x fa-times-circle float-left mr-2"></i> ' . $error . '</div>';
        }
        $output .= '</div>';
      }

      if (isset($messages['warning'])) {
        $output .= '<div class="alert alert-warning">';
        foreach ($messages['warning'] as $warning) {
          $output .= '<div class="clearfix"><i class="fas fa-5x fa-times-circle float-left mr-2"></i> ' . $warning . '</div>';
        }
        $output .= '</div>';
      }

      if (isset($messages['info'])) {
        $output .= '<div class="alert alert-info">';
        foreach ($messages['info'] as $info) {
          $output .= '<div class="clearfix"><i class="fas fa-5x fa-exclamation-circle float-left mr-2"></i> ' . $info . '</div>';
        }
        $output .= '</div>';
      }

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
          'title' => 'Content Width',
          'value' => '6',
          'desc' => 'What width container should the content be shown in? (12 = full width, 6 = half width).',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '600',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
