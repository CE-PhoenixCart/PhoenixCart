<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

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
      $this->query = $GLOBALS['db']->query($table_definition['sql']);
    }

    public function draw_pages_form($parameters = []) {
      $pages = [];
      for ($i = 1; $i <= $this->page_count; $i++) {
        $pages[] = ['id' => $i, 'text' => $i];
      }

      $form = new Form('pages', $GLOBALS['Admin']->link($GLOBALS['PHP_SELF']), 'get');

      foreach ($parameters as $key => $value) {
        $form->hide($key, $value);
      }

      return $form->hide_session_id()
           . new Select($page_name, $pages, [
               'value' => $this->current_page_number,
               'onchange' => 'this.form.submit();',
             ]) . '</form>';
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
