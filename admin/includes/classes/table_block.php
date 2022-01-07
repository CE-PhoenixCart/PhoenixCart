<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class tableBlock {

    public $table_row_parameters = '';
    public $table_data_parameters = '';

    public function __construct() {}

    public function tableBlock($contents) {
      $tableBox_string = '';

      for ($i=0, $n=count($contents); $i<$n; $i++) {
        $tableBox_string .= '<tr';
        if (!Text::is_empty($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && !Text::is_empty($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>';

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $y=count($contents[$i]); $x<$y; $x++) {
            if (isset($contents[$i][$x]['text']) && !Text::is_empty($contents[$i][$x]['text'])) {
              $tableBox_string .= '<td';
              if (isset($contents[$i][$x]['class']) && !Text::is_empty($contents[$i][$x]['class'])) $tableBox_string .= ' class="' . $contents[$i][$x]['class'] . '"';
              if (isset($contents[$i][$x]['params']) && !Text::is_empty($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (!Text::is_empty($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && !Text::is_empty($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && !Text::is_empty($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</td>' . PHP_EOL;
            }
          }
        } else {
          $tableBox_string .= '<td';
          if (isset($contents[$i]['class']) && !Text::is_empty($contents[$i]['class'])) $tableBox_string .= ' class="' . $contents[$i]['class'] . '"';
          if (isset($contents[$i]['params']) && !Text::is_empty($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (!Text::is_empty($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . PHP_EOL;
        }

        $tableBox_string .= '</tr>';
      }

      return $tableBox_string;
    }

  }
