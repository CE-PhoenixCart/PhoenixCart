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

  $currency_select_array = [['id' => '', 'text' => TEXT_INFO_COMMON_CURRENCIES]];
  foreach (array_diff_key($currency_select, Guarantor::ensure_global('currencies')->currencies) as $cs) {
    $currency_select_array[] = ['id' => $cs['code'], 'text' => '[' . $cs['code'] . '] ' . $cs['title']];
  }

  $currency_sql = "SELECT * FROM currencies ORDER BY title";
  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_CURRENCY_NAME,
        'is_heading' => true,
        'function' => function ($row) {
          $value = $row['title'];
          if (DEFAULT_CURRENCY == $row['code']) {
            $value .= ' (' . TEXT_DEFAULT . ')';
          }
          return $value;
        },
      ],
      [
        'name' => TABLE_HEADING_CURRENCY_CODES,
        'function' => function ($row) {
          return $row['code'];
        },
      ],
      [
        'name' => TABLE_HEADING_CURRENCY_VALUE,
        'function' => function ($row) {
          return number_format($row['value'], 8);
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-right',
        'function' => function ($row) {
          return ((isset($row['info']->currencies_id))
                ? '<i class="fas fa-chevron-circle-right text-info"></i>'
                : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>');
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_CURRENCIES,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'cID',
    'sql' => $currency_sql,
  ];

  $table_definition['split'] = new Paginator($table_definition);
  $link = $Admin->link()->retain_query_except(['action']);
  $table_definition['function'] = function (&$row) use ($link, $action, &$table_definition) {
    $link->set_parameter('cID', $row['currencies_id']);
    if (!isset($table_definition['info']) && (!isset($_GET['cID']) || ($_GET['cID'] == $row['currencies_id'])) && (substr($action, 0, 3) != 'new')) {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['onclick'] = (clone $link)->set_parameter('action', 'edit');
      $row['css'] = ' class="table-active"';
      $row['info']->link = clone $link;
    } else {
      $row['onclick'] = clone $link;
      $row['css'] = '';
    }
  };

  $admin_hooks->set('buttons', 'update_installed_currencies', function () use ($Admin) {
    return ( defined('MODULE_ADMIN_CURRENCIES_INSTALLED') && !Text::is_empty(MODULE_ADMIN_CURRENCIES_INSTALLED) )
         ? '<p class="mr-2">'
           . $Admin->button(IMAGE_UPDATE_CURRENCIES, 'fas fa-money-bill-alt', 'btn-success btn-block', $Admin->link('currencies.php', ['action' => 'update', 'formid' => $_SESSION['sessiontoken']]))
         . '</p>'
         : '<div class="alert alert-warning mr-2">'
           . sprintf(ERROR_INSTALL_CURRENCY_CONVERTER, $Admin->link('modules.php', ['set' => 'currencies']))
         . '</div>';
  });

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
    <div class="col-sm-4 text-right align-self-center">
      <?= empty($action)
        ? $Admin->button(IMAGE_NEW_CURRENCY, 'fas fa-plus', 'btn-danger', $Admin->link('currencies.php', ['action' => 'new']))
        : $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $Admin->link())
      ?>
    </div>
  </div>

<?php
  $table_definition['split']->display_table();

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
