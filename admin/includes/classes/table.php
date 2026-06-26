<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/

class Table {
  public $table_definition;

  public function __construct(&$table_definition) {
    $this->table_definition =& $table_definition;

    if (!isset($this->table_definition['data']) || !is_array($this->table_definition['data'])) {
      throw new InvalidArgumentException('Table requires a "data" array in table_definition');
    }

    if (!isset($this->table_definition['columns']) || !is_array($this->table_definition['columns']) || empty($this->table_definition['columns'])) {
      throw new InvalidArgumentException('Table requires a non-empty "columns" array in table_definition');
    }

    $GLOBALS['admin_hooks']->cat('constructTable', $this);

    $this->table_definition['tfoot'] = $this->table_definition['tfoot'] ?? [];

    $this->table_definition['split'] = new class($table_definition['data']) {
      protected array $data;

      public function __construct(array $data) {
        $this->data = $data;
      }

      public function fetch(): array {
        return $this->data;
      }

      public function display_count(): string {
        $count = count($this->data);
        return sprintf(TEXT_DISPLAY_NUMBER_OF_RESULTS, 1, $count, $count);
      }

      public function draw_pages_form(): string {
        return '';
      }
    };
  }

  /**
   * Display the table using the included template.
   *
   * @param array $extra_vars Optional additional variables to pass to template.
   */
  public function display_table(array $extra_vars = []): void {
    $table_definition = $this->table_definition;

    if (!empty($extra_vars)) {
      extract($extra_vars);
    }

    include DIR_FS_ADMIN . 'includes/components/non_paginated_table.php';
  }
}
