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
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
<?php
  foreach ($table_definition['columns'] as $column) {
    echo '<th';
    if (isset($column['class'])) {
      echo ' class="', $column['class'], '"';
    }
    echo '>', $column['name'], '</th>';
  }
?>
            </tr>
          </thead>
          <tbody>
<?php
  foreach ($table_definition['split']->fetch() as $row) {
?>
            <tr<?= $row['css'] ?> onclick="document.location.href='<?= $row['onclick'] ?>'">
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
        </table>
      </div>

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
  $table_definition['box']();
  if ( ([] !== $table_definition['heading']) && ([] !== $table_definition['contents']) ) {
    echo '<div class="col-12 col-sm-4">';
      $box = new box();
      echo $box->infoBox($table_definition['heading'], $table_definition['contents']);
    echo '</div>';
  }
?>

  </div>
