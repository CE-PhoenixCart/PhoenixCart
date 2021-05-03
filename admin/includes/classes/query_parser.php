<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class query_parser {

    protected $sql_query;
    protected $to;
    protected $from;
    protected $where_position;

    public function __construct($sql_query) {
      $this->sql_query = $sql_query;

      $this->to = strlen($sql_query);
      $this->from = stripos($sql_query, ' FROM');
      $this->where_position = strripos(substr($sql_query, $this->from), ' WHERE') ?: 0;
      $this->where_position += $this->from;

      foreach ([' GROUP BY', ' HAVING', ' ORDER BY'] as $needle) {
        $this->lower_end($needle);
      }
    }

    protected function lower_end($needle) {
      $position = strripos($this->sql_query, $needle);
      if ($position && ($position > $this->where_position) && ($position < $this->to)) {
        $this->to = $position;
      }
    }

    public function count() {
      $count_query = $GLOBALS['db']->query("SELECT COUNT(*) AS total "
        . substr($this->sql_query, $this->from, ($this->to - $this->from)));
      return $count_query->fetch_assoc()['total'];
    }

  }
