<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['delete'];
  require 'includes/application_top.php';
  require 'includes/segments/process_action.php';

  $currency_select = [
    'USD' => ['title' => 'U.S. Dollar', 'code' => 'USD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'EUR' => ['title' => 'Euro', 'code' => 'EUR', 'symbol_left' => '', 'symbol_right' => '€', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'JPY' => ['title' => 'Japanese Yen', 'code' => 'JPY', 'symbol_left' => '¥', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'GBP' => ['title' => 'Pounds Sterling', 'code' => 'GBP', 'symbol_left' => '£', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'CHF' => ['title' => 'Swiss Franc', 'code' => 'CHF', 'symbol_left' => '', 'symbol_right' => 'CHF', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'],
    'AUD' => ['title' => 'Australian Dollar', 'code' => 'AUD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'CAD' => ['title' => 'Canadian Dollar', 'code' => 'CAD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'SEK' => ['title' => 'Swedish Krona', 'code' => 'SEK', 'symbol_left' => '', 'symbol_right' => 'kr', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'],
    'HKD' => ['title' => 'Hong Kong Dollar', 'code' => 'HKD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'NOK' => ['title' => 'Norwegian Krone', 'code' => 'NOK', 'symbol_left' => 'kr', 'symbol_right' => '', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'],
    'NZD' => ['title' => 'New Zealand Dollar', 'code' => 'NZD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'MXN' => ['title' => 'Mexican Peso', 'code' => 'MXN', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'SGD' => ['title' => 'Singapore Dollar', 'code' => 'SGD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'BRL' => ['title' => 'Brazilian Real', 'code' => 'BRL', 'symbol_left' => 'R$', 'symbol_right' => '', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'],
    'CNY' => ['title' => 'Chinese RMB', 'code' => 'CNY', 'symbol_left' => '￥', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'CZK' => ['title' => 'Czech Koruna', 'code' => 'CZK', 'symbol_left' => '', 'symbol_right' => 'Kč', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'],
    'DKK' => ['title' => 'Danish Krone', 'code' => 'DKK', 'symbol_left' => '', 'symbol_right' => 'kr', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'],
    'HUF' => ['title' => 'Hungarian Forint', 'code' => 'HUF', 'symbol_left' => '', 'symbol_right' => 'Ft', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'ILS' => ['title' => 'Israeli New Shekel', 'code' => 'ILS', 'symbol_left' => '₪', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'INR' => ['title' => 'Indian Rupee', 'code' => 'INR', 'symbol_left' => 'Rs.', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'MYR' => ['title' => 'Malaysian Ringgit', 'code' => 'MYR', 'symbol_left' => 'RM', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'PHP' => ['title' => 'Philippine Peso', 'code' => 'PHP', 'symbol_left' => 'Php', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'PLN' => ['title' => 'Polish Zloty', 'code' => 'PLN', 'symbol_left' => '', 'symbol_right' => 'zł', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'],
    'THB' => ['title' => 'Thai Baht', 'code' => 'THB', 'symbol_left' => '', 'symbol_right' => '฿', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
    'TWD' => ['title' => 'Taiwan New Dollar', 'code' => 'TWD', 'symbol_left' => 'NT$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'],
  ];
  
  array_multisort($currency_select);

  $currency_select_array = [['id' => '', 'text' => TEXT_INFO_COMMON_CURRENCIES]];
  foreach (array_diff_key($currency_select, Guarantor::ensure_global('currencies')->currencies) as $cs) {
    $currency_select_array[] = ['id' => $cs['code'], 'text' => '[' . $cs['code'] . '] ' . $cs['title']];
  }

  require 'includes/template_top.php';
?>

<script>
var currency_select = new Array();
<?php
  foreach ($currency_select_array as $cs) {
    if (!empty($cs['id'])) {
      echo 'currency_select["' . $cs['id'] . '"] = new Array("' . $currency_select[$cs['id']]['title'] . '", "' . $currency_select[$cs['id']]['symbol_left'] . '", "' . $currency_select[$cs['id']]['symbol_right'] . '", "' . $currency_select[$cs['id']]['decimal_point'] . '", "' . $currency_select[$cs['id']]['thousands_point'] . '", "' . $currency_select[$cs['id']]['decimal_places'] . '");' . "\n";
    }
  }
?>

function updateForm() {
  var cs = document.forms["currencies"].cs[document.forms["currencies"].cs.selectedIndex].value;

  document.forms["currencies"].title.value = currency_select[cs][0];
  document.forms["currencies"].code.value = cs;
  document.forms["currencies"].symbol_left.value = currency_select[cs][1];
  document.forms["currencies"].symbol_right.value = currency_select[cs][2];
  document.forms["currencies"].decimal_point.value = currency_select[cs][3];
  document.forms["currencies"].thousands_point.value = currency_select[cs][4];
  document.forms["currencies"].decimal_places.value = currency_select[cs][5];
  document.forms["currencies"].value.value = 1;
}
</script>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?= 
      $Admin->button(GET_HELP, '', 'btn-dark me-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      empty($action)
        ? $Admin->button(IMAGE_NEW_CURRENCY, 'fas fa-plus', 'btn-danger', $Admin->link('currencies.php', ['action' => 'new']))
        : $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $Admin->link())
      ?>
    </div>
  </div>

<?php
  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
