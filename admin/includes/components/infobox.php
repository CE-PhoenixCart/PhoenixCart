<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = '';
  $contents = [];
  require $action_file;

  if ( !Text::is_empty($heading) && ([] !== $contents) ) {
    $parameters = [
      'heading' => &$heading,
      'contents' => &$contents,
    ];
    $GLOBALS['admin_hooks']->cat('infoBox', $parameters);

    if (isset($contents['form'])) {
      $form_start = $contents['form'] . PHP_EOL;
      $form_close = '</form>' . PHP_EOL;
      unset($contents['form']);
    } else {
      $form_start = '';
      $form_close = '';
    }
?>
    <div class="col-12 col-sm-4">
      <div class="table-responsive">
<?php
    if ('' !== $form_start) {
      echo $form_start;
    }
?>
        <table class="table table-striped table-hover">
          <thead class="table-light">
            <tr>
              <th><?= $heading ?></th>
            </tr>
          </thead>
          <tbody>
<?php
    foreach ($contents as $row) {
      echo '<tr>' . PHP_EOL;

      echo '<td';
      if (isset($row['class']) && !Text::is_empty($row['class'])) {
        echo ' class="' . $row['class'] . '"';
      }
      echo '>' . $row['text'] . '</td>' . PHP_EOL;
      echo '</tr>' . PHP_EOL;
    }
?>

          </tbody>
        </table>
<?php
    if ('' !== $form_close) {
      echo $form_close;
    }
?>
      </div>
    </div>
<?php
  }
?>
