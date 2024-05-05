<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($table_definition['info']) && is_object($table_definition['info'])) {
    $heading = $table_definition['info']->module;

    $contents[] = [
      'text' => TEXT_INFO_IDENTIFIER . ' '
              . (empty($table_definition['info']->identifier)
              ? '(empty)'
              : '<a href="' . $GLOBALS['Admin']->link('action_recorder.php', ['search' => $table_definition['info']->identifier])
                . '"><u>' . htmlspecialchars($table_definition['info']->identifier) . '</u></a>'),
    ];
    $contents[] = ['text' => sprintf(TEXT_INFO_DATE_ADDED, $GLOBALS['date_time_formatter']->format((new Date($table_definition['info']->date_added))->get_timestamp()))];
  }
