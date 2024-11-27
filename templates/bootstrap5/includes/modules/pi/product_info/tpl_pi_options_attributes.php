<div class="<?= PI_OA_CONTENT_WIDTH ?> pi-options-attributes">
  <p class="fs-6 fw-semibold mb-1"><?= PI_OA_HEADING_TITLE ?></p>

  <?php
  foreach ($options as $option) {
    $input_id = "input_{$option['id']}";
    
    echo '<div class="form-floating mb-2">';
      $input = new Select("id[{$option['id']}]", $option['choices'], ['id' => $input_id, 'class' => 'form-select']);
      $input->set_selection($option['selection']);
      if (PI_OA_ENFORCE === 'True') {
        $input = $input->require() . PHP_EOL . FORM_REQUIRED_INPUT;
      }
      echo $input . PHP_EOL;
      echo '<label for="', $input_id, '">', $option['name'], '</label>';
    echo '</div>';
  }
  ?>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
