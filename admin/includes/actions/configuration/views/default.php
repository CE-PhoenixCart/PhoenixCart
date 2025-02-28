<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_CONFIGURATION_TITLE,
        'is_heading' => true,
        'function' => function ($row) {
          return $row['configuration_title'];
        },
      ],
      [
        'name' => TABLE_HEADING_CONFIGURATION_VALUE,
        'function' => function ($row) {
          if (Text::is_empty($row['use_function'])) {
            $cfg_value = $row['configuration_value'];
          } else {
            if (strpos($row['use_function'], '->')) {
// if there is a -> with something before it
// make sure that the something is instantiated
              list ($class, $method) = explode('->', $row['use_function'], 2);
              $use_function = [Guarantor::ensure_global($class), $method];
            } else {
              $use_function = $row['use_function'];
            }

            if (is_callable($use_function)) {
              $cfg_value = $use_function($row['configuration_value']);
            } else {
              $cfg_value = 0;
              $GLOBALS['messageStack']->add(
                sprintf(
                  WARNING_INVALID_USE_FUNCTION,
                  $row['use_function'],
                  $row['configuration_title']),
                'warning');
            }
          }

          return htmlspecialchars($cfg_value);
        }
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->configuration_id) && ($row['configuration_id'] == $row['info']->configuration_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_ENTRIES,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'cID',
    'db_id' => 'configuration_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => sprintf(<<<'EOSQL'
SELECT configuration_id, configuration_title, configuration_value, use_function
 FROM configuration
 WHERE configuration_group_id = %d
 ORDER BY sort_order
EOSQL
      , (int)$gID),
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = $GLOBALS['link']->set_parameter(
      'cID', $row['configuration_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['cID']) || ($_GET['cID'] == $row['configuration_id']))
      && !Text::is_prefixed_by($GLOBALS['action'], 'new'))
    {
      $extra = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT configuration_key, configuration_description, date_added, last_modified, set_function
 FROM configuration
 WHERE configuration_id = %d
EOSQL
        , (int)$row['configuration_id']))->fetch_assoc();

      $data = array_merge($row, $extra);
      $table_definition['info'] = new objectInfo($data);
      $row['info'] = &$table_definition['info'];

      $row['css'] = ' class="table-active"';
      $row['onclick'] = (clone $row['onclick'])->set_parameter('action', 'edit');
    } else {
      $row['css'] = '';
    }
  };

  $table_definition['split'] = new Paginator($table_definition);
  
  $table_definition['split']->display_table();
  