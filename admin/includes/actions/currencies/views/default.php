<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

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
        'class' => 'text-end',
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
         ? '<p class="d-grid mt-2 me-2">'
           . $Admin->button(IMAGE_UPDATE_CURRENCIES, 'fas fa-money-bill-alt', 'btn-success', $Admin->link('currencies.php', ['action' => 'update', 'formid' => $_SESSION['sessiontoken']]))
         . '</p>'
         : '<div class="alert alert-warning me-2">'
           . sprintf(ERROR_INSTALL_CURRENCY_CONVERTER, $Admin->link('modules.php', ['set' => 'currencies']))
         . '</div>';
  });
  
  $table_definition['split']->display_table();
  