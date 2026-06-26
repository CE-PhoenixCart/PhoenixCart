<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/
?>

  <div class="row g-0">
    <div class="col-12">
      <div class="table-responsive">
        <table class="table <?= $table_definition['style'] ?? 'table-striped' ?>">
          <thead class="table-dark">
            <tr>
              <?php
              foreach ($table_definition['columns'] as $column) {
                echo '<th';
                if (isset($column['class'])) {
                  echo ' class="', $column['class'], '"';
                }
                echo '>', $column['name'], '</th>', PHP_EOL;
              }
              ?>
            </tr>
          </thead>
          <tbody>
          <?php
            foreach ($table_definition['split']->fetch() as $row) {
            ?>
            <tr>
              <?php
              foreach ($table_definition['columns'] as $column) {
                if ($column['is_heading'] ?? false) {
                  echo '<th scope="row"';
                  $close = '</th>';
                } else {
                  echo '<td';
                  $close = '</td>';
                }

                if (isset($column['class'])) {
                  echo ' class="', $column['class'], '"';
                }

                echo '>', $column['function']($row), $close, PHP_EOL;
              }
              ?>
            </tr>
            <?php
            }
          ?>
          </tbody>
          <?php
          if (!empty($table_definition['tfoot']) && is_array($table_definition['tfoot'])) {
            echo '<tfoot>';
            foreach ($table_definition['tfoot'] as $footer_row) {
              echo '<tr>';
                foreach ($footer_row as $cell) {
                  $tag = $cell['tag'] ?? 'td';
                  $attrs = $cell['attributes'] ?? '';
                  $content = $cell['content'] ?? '';
                  echo "<{$tag} {$attrs}>{$content}</{$tag}>";
                }
              echo '</tr>';
            }
            echo '</tfoot>';
          }
          ?>
        </table>
      </div>

      <?= $GLOBALS['admin_hooks']->cat($table_definition['hooks']['button'] ?? 'buttons') ?>
    </div>
  </div>
