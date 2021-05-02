<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2021 osCommerce

  Released under the GNU General Public License
*/

  class Paginator {

    protected $table_definition;
    protected $current_page_number;
    protected $row_count;
    protected $page_count;
    public $query;

    public function __construct(&$table_definition) {
      $this->table_definition =& $table_definition;
      $this->current_page_number = empty($table_definition['page']) ? 1 : (int)$table_definition['page'];

      $this->row_count = (new query_parser($table_definition['sql']))->count();

      $this->page_count = (int)ceil($this->row_count / $this->table_definition['rows_per_page']);
      if ($this->current_page_number > $this->page_count) {
        $this->current_page_number = $this->page_count;
      }
      $table_definition['page'] = $this->current_page_number;
      $offset = ($this->table_definition['rows_per_page'] * ($this->current_page_number - 1));
      $table_definition['sql'] .= " LIMIT " . max($offset, 0) . ", " . $this->table_definition['rows_per_page'];
      $this->query = tep_db_query($table_definition['sql']);
    }

    public function draw_pages_form() {
      $pages = [];
      for ($i = 1; $i <= $this->page_count; $i++) {
        $pages[] = ['id' => $i, 'text' => $i];
      }

      $form = tep_draw_form('pages', $GLOBALS['PHP_SELF'], '', 'get')
            . tep_draw_pull_down_menu($page_name, $pages,
                $this->current_page_number, 'onchange="this.form.submit();"');
      if (strpos($parameters, '=')) {
        foreach (explode('&', trim($parameters, '&')) as $pair) {
          list($key, $value) = explode('=', $v);
          $form .= tep_draw_hidden_field(rawurldecode($key), rawurldecode($value));
        }
      }

      return $form . tep_hide_session_id() . '</form>';
    }

    public function display_count() {
      $to_num = ($this->table_definition['rows_per_page'] * $this->current_page_number);
      if ($to_num > $this->row_count) {
        $to_num = $this->row_count;
      }

      if ($to_num > 0) {
        $from_num = ($this->table_definition['rows_per_page'] * ($this->current_page_number - 1)) + 1;
      } else {
        $from_num = 0;
      }

      return sprintf($this->table_definition['count_text'],
        $from_num, $to_num, $this->row_count);
    }

    public function display_table() {
      $table_definition =& $this->table_definition;
      include DIR_FS_ADMIN . 'includes/components/paginated_table.php';
    }

    public function fetch() {
      while ($row = $this->query->fetch_assoc()) {
        $this->table_definition['function']($row);
        yield $row;
      }
    }

  }
