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

      $GLOBALS['admin_hooks']->cat('constructPaginator', $this);

      $this->row_count = $table_definition['row_count']
                      ?? (new query_parser($table_definition['sql']))->count();

      if (!isset($this->table_definition['rows_per_page'])) {
        $this->table_definition['rows_per_page'] = MAX_DISPLAY_SEARCH_RESULTS;
      }

      $this->page_count = (int)ceil($this->row_count / $this->table_definition['rows_per_page']);
      if ($this->current_page_number > $this->page_count) {
        $this->current_page_number = $this->page_count;
      }

      $table_definition['page'] = $this->current_page_number;
      $offset = ($this->table_definition['rows_per_page'] * ($this->current_page_number - 1));
      $table_definition['sql'] .= " LIMIT " . max($offset, 0) . ", " . $this->table_definition['rows_per_page'];
      $this->query = $GLOBALS['db']->query($table_definition['sql']);

      if (!isset($table_definition['parameters'])) {
        $table_definition['parameters'] = array_diff_key($_GET, ['page' => 0]);

        if (isset($this->table_definition['web_id'])) {
          unset($table_definition['parameters'][$this->table_definition['web_id']]);
        }
      }
    }

    public function display_count() {
      $to = ($this->table_definition['rows_per_page'] * $this->current_page_number);
      if ($to > $this->row_count) {
        $to = $this->row_count;
      }

      $from = ($to > 0)
            ? ($this->table_definition['rows_per_page'] * ($this->current_page_number - 1)) + 1
            : 0;

      return sprintf($this->table_definition['count_text'],
        $from, $to, $this->row_count);
    }

    public function display_table() {
      $table_definition =& $this->table_definition;
      include DIR_FS_ADMIN . 'includes/components/paginated_table.php';
    }

    public function draw_pages_form() {
      $pages = [];
      for ($i = 1; $i <= $this->page_count; $i++) {
        $pages[] = ['id' => $i, 'text' => $i];
      }

      $form = new Form('pages', $GLOBALS['Admin']->link(), 'get');

      foreach ($this->table_definition['parameters'] ?? [] as $key => $value) {
        $form->hide($key, $value);
      }

      $output = $form->hide_session_id();
        $output .= '<div class="input-group">';
          $output .= '<span class="input-group-text" id="p">' . SPLIT_PAGES . '</span>';
          $output .= new Select($this->table_definition['page_name'] ?? 'page', $pages, [
                 'value' => $this->current_page_number,
                 'onchange' => 'this.form.submit();',
                 'class' => 'form-select',
                 'aria-describedby' => 'p',
               ]);
        $output .= '</div>';
      $output .= '</form>';
      
      return $output;
    }

    public function process_default(&$row) {
      $row['onclick'] = $GLOBALS['Admin']->link();
      $row['onclick']->retain_query_except(['action'])->set_parameter(
        $this->table_definition['web_id'],
        (int)$row[$this->table_definition['db_id']]);

      if (!isset($this->table_definition['info'])
        && (!isset($_GET[$this->table_definition['web_id']])
          || ($_GET[$this->table_definition['web_id']] == $row[$this->table_definition['db_id']])))
      {
        $this->table_definition['info'] = new objectInfo($row);
        $row['info'] = &$this->table_definition['info'];

        $row['onclick']->set_parameter('action', 'edit');
        $row['css'] = ' class="table-active"';
      } else {
        $row['css'] = '';
      }
    }

    public function fetch() {
      while ($row = $this->query->fetch_assoc()) {
        ($this->table_definition['function'] ?? [$this, 'process_default'])($row);
        yield $row;
      }
    }

    public function get_current_page_number() {
      return $this->current_page_number;
    }

    public function get_table_definition() {
      return $this->table_definition;
    }

    public function set_current_page_number($page_number) {
      $this->current_page_number = $page_number;
    }

  }
