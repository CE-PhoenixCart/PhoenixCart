<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class database_core extends mysqli {

    /**
     * Connect to a database, with the correct charset and sql_mode set.
     *
     * @param string $server
     * @param string $username
     * @param string $password
     * @param string $database
     */
    public function __construct($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE) {
      parent::__construct($server, $username, $password, $database);

      if ( is_null($this->connect_error) ) {
        $this->set_charset('utf8mb4');
      }

      @parent::query("SET SESSION sql_mode=''");
    }

    /**
     * Report a fatal error if a database query fails.
     *
     * @param string $sql
     */
    public function report_error($sql) {
      if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
        error_log("ERROR: [{$this->errno}] {$this->error}\n" . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
      }

      foreach (str_split("DB: [{$this->errno}] {$this->error} from <$sql>", 1024) as $line) {
        trigger_error($line, E_USER_ERROR);
      }

      if (ini_get('display_errors')) {
        die('<font color="#000000"><strong>' . $this->errno . ' - ' . $this->error . '<br><br>' . $sql . '<br><br><small><font color="#ff0000">[PHOENIX FATAL]</font></small><br><br></strong></font>');
      } else {
        die('<br><small><font color="#ff0000">[PHOENIX FATAL]</font></small><br>');
      }
    }

    /**
     * Run SQL query, logging and reporting errors if necessary.
     *
     * @param string $sql
     * @param string $resultmode
     * @return boolean
     */
    public function query($sql, $resultmode = MYSQLI_STORE_RESULT) {
      if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
        error_log('QUERY: ' . $sql . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
      }

      return parent::query($sql, $resultmode) ?: $this->report_error($sql);
    }

    /**
     * Escape a string for safe insertion into a SQL statement.
     *
     * @param string $input
     * @return string
     */
    public function escape(string $input) {
      return $this->real_escape_string($input);
    }

    /**
     * Escape any non-whitelisted string.
     *
     * @param string $value
     * @return string
     */
    public function normalize_value($value) {
      switch (strtoupper("$value")) {
        case 'NOW()':
          return 'NOW()';
        case 'NULL':
          return 'NULL';
        default:
          return "'" . $this->real_escape_string($value) . "'";
      }
    }

    /**
     * Perform an insert or update on the specified table.
     *
     * @param string $table
     * @param array $data Column names as keys and values as values.
     * @param string $action Defaults to insert.
     * @param string $parameters Only needed for updates; the where clause.
     * @return boolean
     */
    public function perform($table, $data, $action = 'insert', $parameters = '') {
      if ($action == 'insert') {
        $query = 'INSERT INTO ' . $table . ' (' . implode(', ', array_keys($data))
               . ') VALUES ('
               . implode(', ', array_map([$this, 'normalize_value'], $data)) . ')';
      } elseif ($action == 'update') {
        $query = 'UPDATE ' . $table . ' SET '
               . implode(', ', array_map(function ($column, $value) {
          return "$column = $value";
        }, array_keys($data), array_map([$this, 'normalize_value'], $data)))
               . ' WHERE ' . $parameters;
      }

      return $this->query($query);
    }

    /**
     * Fetch all the results from a query.
     *
     * @param mysqli_result|string $db_query
     * @return []
     */
    public function fetch_all($db_query) {
      if (!($db_query instanceof mysqli_result) && is_string($db_query)) {
        $db_query = $this->query($db_query);
      }

      if (method_exists($db_query, 'fetch_all')) {
        return $db_query->fetch_all(MYSQLI_ASSOC);
      }

      $results = [];
      while ($result = $db_query->fetch_assoc()) {
        $results[] = $result;
      }

      return $results;
    }

    /**
     * Copy rows in the specified tables.
     *
     * @param array $db The columns for each table.
     * @param string $key The column to use to select the row to be copied.
     * @param string $value The value of the $key column.
     * @return boolean|int The auto-increment ID or false.
     */
    public function copy($db, $key, $value) {
      $key_value = false;
      foreach ($db as $table => $columns) {
        $values = [];
        foreach ($columns as $name => $v) {
          if (is_null($v) && $key_value && ($name === $key)) {
            $v = $key_value;
          }

          $values[] = ($v ?? $name);
        }

        $this->query('INSERT INTO ' . $table
          . ' (' . implode(', ', array_keys($columns))
          . ') SELECT ' . implode(', ', $values)
          . ' FROM ' . $table . ' WHERE ' . $key . ' = ' . $value);

        if (!$key_value) {
          $key_value = mysqli_insert_id($this);
        }
      }

      return $key_value;
    }

  }
