<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db') {
    $GLOBALS[$link] = new Database($server, $username, $password, $database);

    return $GLOBALS[$link];
  }

  function tep_db_close($link = 'db') {
    return $GLOBALS[$link]->close();
  }

  function tep_db_error($query, $errno, $error) {
    trigger_error('The tep_db_error function has been deprecated.', E_USER_DEPRECATED);
    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
      error_log("ERROR: [$errno] $error\n" . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    die('<font color="#000000"><strong>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[Phoenix STOP]</font></small><br><br></strong></font>');
  }

  function tep_db_query($query, $link = 'db') {
    return $GLOBALS[$link]->query($query);
  }

  function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db') {
    return $GLOBALS[$link]->perform($table, $data, $action, $parameters);
  }

  function tep_db_fetch_array($db_query) {
    return $db_query->fetch_assoc();
  }

  function tep_db_num_rows($db_query) {
    return mysqli_num_rows($db_query);
  }

  function tep_db_data_seek($db_query, $row_number) {
    return $db_query->data_seek($row_number);
  }

  function tep_db_insert_id($link = 'db') {
    return $GLOBALS[$link]->insert_id;
  }

  function tep_db_free_result($db_query) {
    trigger_error('The tep_db_free_result function has been deprecated.', E_USER_DEPRECATED);
    return $db_query->free_result();
  }

  function tep_db_fetch_fields($db_query) {
    trigger_error('The tep_db_fetch_fields function has been deprecated.', E_USER_DEPRECATED);
    return $db_query->fetch_field();
  }

  function tep_db_output($string) {
    return htmlspecialchars($string);
  }

  function tep_db_input($string, $link = 'db') {
    return $GLOBALS[$link]->real_escape_string($string);
  }

  function tep_db_prepare_input($input) {
    return Text::input($input);
  }

  function tep_db_affected_rows($link = 'db') {
    return mysqli_affected_rows($GLOBALS[$link]);
  }

  function tep_db_get_server_info($link = 'db') {
    trigger_error('The tep_db_get_server_info function has been deprecated.', E_USER_DEPRECATED);
    return $GLOBALS[$link]->server_info;
  }
