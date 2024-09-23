<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class pi_qty_input extends abstract_module {

    const CONFIG_KEY_BASE = 'PI_QTY_INPUT_';

    public $group = 'pi_modules_a';

    function __construct() {
      parent::__construct();

      $this->group = basename(dirname(__FILE__));

      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
      $this->description .= '<div class="alert alert-info">' . cm_pi_modular::display_layout() . '</div>';

      if ( $this->enabled ) {
        $this->group = 'pi_modules_' . strtolower(PI_QTY_INPUT_GROUP);
      }
    }

    function getOutput() {
      $tpl_data = ['group' => $this->group, 'file' => __FILE__];
      include 'includes/modules/block_template.php';

      if (PI_QTY_INPUT_BUTTONS == 'True') {
        $pi_qty_input_js = <<<EOJS
<script>document.addEventListener('DOMContentLoaded', function() { let pi_qty = parseInt(document.getElementById('pi-qty-spin').value, 10) || 1; document.querySelectorAll('.spinner').forEach(function(spinner) { spinner.addEventListener('click', function() { const pi_action = this.getAttribute('data-spin'); if (pi_action === 'plus') { pi_qty++; } else { pi_qty--; } if (pi_qty < 1) pi_qty = 1; document.getElementById('pi-qty-spin').value = pi_qty; return false; }); }); });
</script>
EOJS;

        $GLOBALS['Template']->add_block($pi_qty_input_js, 'footer_scripts');
      }
    }

    protected function get_parameters() {
      return [
        'PI_QTY_INPUT_STATUS' => [
          'title' => 'Enable Qty Module',
          'value' => 'True',
          'desc' => 'Should this module be shown in the &pi; layout?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'PI_QTY_INPUT_GROUP' => [
          'title' => 'Module Display',
          'value' => 'C',
          'desc' => 'Where should this module display on the product info page?',
          'set_func' => "Config::select_one(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'], ",
        ],
        'PI_QTY_INPUT_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12 mb-2',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        'PI_QTY_INPUT_BUTTONS' => [
          'title' => 'Add Spinner Buttons',
          'value' => 'True',
          'desc' => 'Add -/+ buttons onto the number input?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'PI_QTY_INPUT_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '319',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
