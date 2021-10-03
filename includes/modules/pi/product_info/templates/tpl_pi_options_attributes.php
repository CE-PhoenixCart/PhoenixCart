<div class="col-sm-<?= (int)PI_OA_CONTENT_WIDTH ?> pi-options-attributes mt-2">
  <h6><?= PI_OA_HEADING_TITLE ?></h6>

  <?php
  foreach ($options as $option) {
    $input_id = "input_{$option['id']}";

    echo '<div class="form-group row">' . PHP_EOL;
    echo '<label for="' . $input_id . '" class="col-form-label col-sm-3 text-left text-sm-right">' . $option['name'] . '</label>' . PHP_EOL;
    echo '<div class="col-sm-9">' . PHP_EOL;

    $input = new Select("id[{$option['id']}]", $option['choices'], ['id' => $input_id]);
    $input->set_selection($option['selection']);
    if (PI_OA_ENFORCE === 'True') {
      $input = $input->require() . PHP_EOL . FORM_REQUIRED_INPUT;
    }
    echo $input . PHP_EOL;

    echo '</div>' . PHP_EOL;
    echo '</div>' . PHP_EOL;
  }
  ?>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
