<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class sql_file {

    protected $filename;
    protected $directory;
    protected $alias;
    protected $split = false;

    public function __construct($filename, $directory, $alias = null) {
      $this->filename = $filename;
      $this->directory = rtrim($directory, "/\\");
      $this->alias = $alias ?? "{$this->directory}/{$this->filename}";
    }

    public function run_sql($sql) {
      if ($this->split) {
        foreach (
          explode(";\n",
            trim(implode('',
                array_filter(
                  file($sql_file),
                  function ($s) {
                    $s = trim($s);
                    return ('' !== $s) && ('#' !== $s[0]);
                  })), "; \n\r\t\v\0")
          ) as $sql)
        {
          if (!$GLOBALS['db']->query($sql)) {
            return;
          }
        }
      } else {
        $GLOBALS['db']->multi_query($sql);
        while ($GLOBALS['db']->more_results() && $GLOBALS['db']->next_result()) ;
      }
    }

    public function run_sql_or_die($sql) {
      $this->run_sql($sql);

      if (!empty($GLOBALS['db']->errno)) {
        $GLOBALS['db']->report_error($this->filename);
      }
    }

    public static function message($message, $type = 'error') {
      error_log($message);

      if (isset($GLOBALS['messageStack'])) {
        $GLOBALS['messageStack']->add_session($message, $type);
      }
    }

    public function validate_packet($filesize) {
      $maximum = $GLOBALS['db']->query('SELECT @@global.max_allowed_packet')->fetch_array()[0];
      if ($filesize + 1024 > $maximum) {
        $this->split = true;
      }

      return true;
    }

    public function validate_size($file) {
      $filesize = filesize($file);

      return ($filesize > 15000) && $this->validate_packet($filesize);
    }

    public function install() {
      $path = "{$this->directory}/{$this->filename}";
      if (!file_exists($path) || !$this->validate_packet(filesize($path))) {
        return false;
      }

      $this->run_sql(file_get_contents($path));

      if (empty($GLOBALS['db']->errno)) {
        return true;
      }

      foreach (str_split("DB: [{$GLOBALS['db']->errno}] {$GLOBALS['db']->error} from <$path>", 1024) as $line) {
        trigger_error($line, E_USER_WARNING);
      }

      return false;
    }

    public function restore_sql($file) {
      if (is_null($file) || !file_exists($file) || !$this->validate_size($file)) {
        static::message(sprintf(ERROR_INVALID_FILE, $this->alias ?? '[NULL]'));
        return false;
      }

      session_write_close();

      $sql = file_get_contents($file);
      $sql .= sprintf(<<<'EOSQL'
DELETE FROM whos_online;
DELETE FROM sessions;
REPLACE INTO configuration
 (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added)
 VALUES ('Last Database Restore', 'DB_LAST_RESTORE', '%s', 'Last database restore file', 6, 0, NOW());
EOSQL
        , $GLOBALS['db']->escape($this->filename));

      $this->run_sql_or_die($sql);
    }

    public function decompress_and_restore() {
      $restore_file = "{$this->directory}/{$this->filename}";
      if (!file_exists($restore_file)) {
        static::message(sprintf(ERROR_INVALID_FILE, $this->alias));
        return false;
      }

      switch (substr($restore_file, -3)) {
        case 'sql':
          $restore_from = $restore_file;
          $remove_raw = false;
          break;
        case '.gz':
          $restore_from = Text::rtrim_once($restore_file, '.gz');
          exec(LOCAL_EXE_GUNZIP . " $restore_file -c > $restore_from");
          $remove_raw = true;
          break;
        case 'zip':
          $restore_from = Text::rtrim_once($restore_file, '.zip');
          exec(LOCAL_EXE_UNZIP . " $restore_file -d " . DIR_FS_BACKUP);
          $remove_raw = true;
          break;
        default:
          static::message(sprintf(ERROR_INVALID_FILE, $this->alias));
          return false;
      }

      if (false === $this->restore_sql($restore_from)) {
        return false;
      }

      if (!empty($remove_raw)) {
        unlink($restore_from);
      }
    }

    public function restore() {
      $restore_file = "{$this->directory}/{$this->filename}";
      if (!file_exists($restore_file)) {
        static::message(sprintf(ERROR_INVALID_FILE, $this->alias));
        return false;
      }

      return $this->restore_sql($restore_from);
    }

  }
