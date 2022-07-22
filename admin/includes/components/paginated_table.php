<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>

  <div class="row no-gutters">
    <div class="col-12 col-sm-8">
      <div class="table-responsive"><?= $table_definition['form'] ?? '' ?>
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
<?php
  foreach ($table_definition['columns'] as $column) {
    echo '              <th';
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
    $row_attributes = $row['css'];
    if (isset($row['onclick'])) {
      $row_attributes .= <<<"EOJS"
 onclick="document.location.href='{$row['onclick']}'"
EOJS;
    }
?>
            <tr<?= $row_attributes ?>>
<?php
    foreach ($table_definition['columns'] as $column) {
      if ($column['is_heading'] ?? false) {
        echo '              <th scope="row"';
        $close = '</th>';
      } else {
        echo '              <td';
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
        </table>
      <?=
        $table_definition['submit'] ?? '',
        isset($table_definition['form']) ? $table_definition['form']->close() : ''
      ?></div>

      <div class="row my-1">
        <div class="col"><?= $table_definition['split']->display_count() ?></div>
        <div class="col text-right mr-2"><?=
       ($this->page_count <= 1)
       ? sprintf(TEXT_RESULT_PAGE, $this->page_count, $this->page_count)
       : '<div class="input-group">'
         . '<div class="input-group-append">'
           . '<span class="input-group-text" id="p">' . SPLIT_PAGES . '</span>'
         . '</div>'
         . $table_definition['split']->draw_pages_form()
       . '</div>'
      ?></div>
      </div>
      <?= $GLOBALS['admin_hooks']->cat($table_definition['hooks']['button'] ?? 'buttons') ?>
    </div>

<?php
  if (isset($GLOBALS['action']) && ($action_file = $GLOBALS['Admin']->locate('/infoboxes', $GLOBALS['action']))) {
    require DIR_FS_ADMIN . 'includes/components/infobox.php';
  }
?>

  </div>
