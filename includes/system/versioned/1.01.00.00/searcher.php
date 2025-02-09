<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  abstract class searcher {

    protected $db_tables;
    protected $criteria;

    public function __construct($db_tables, $criteria) {
      $this->db_tables = $db_tables;
      $this->criteria = $criteria;
    }

    public function add_db_table($table, $columns) {
      $this->db_tables[$table] = $columns; 
    }

    public function add_column($table, $column) {
      $this->db_tables[$table][] = $column;
    }

    public function add_criterion($column, $value) {
      $this->criteria[$column] = $value;
    }

  }
