<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $parameters = [
    'aria-labelledby' => 'atGenderLabel',
    'class' => 'form-check-input',
  ];

  if (!Text::is_empty(ENTRY_GENDER_TEXT)) {
    $parameters['aria-describedby'] = 'atGender';
  }
  
  if ($this->is_required()) {
    $parameters['required'] = null;
  }
?>

  <div class="row mb-2">
    <label id="atGenderLabel" class="col-form-label col-sm-3 text-start text-sm-end"><?= ENTRY_GENDER ?></label>
    <div class="col-sm-9 ">

<?php
  $tickable = new Tickable('gender', $parameters, 'radio');
?>

  <div class="input-group">
    <div class="input-group-text">
      <?= (clone $tickable)->set('value', 'm')->tick('m' === $gender)->set('id', 'genderM') ?>
    </div>
    <label class="input-group-text bg-white col" for="genderM"><?= MALE ?></label>
    <div class="input-group-text">
      <?= $tickable->set('value', 'f')->tick('f' === $gender)->set('id', 'genderF') ?>
    </div>
    <label class="input-group-text bg-white col" for="genderF"><?= FEMALE ?></label>
    <?php
    if ($this->is_required() && !Text::is_empty(FORM_REQUIRED_INPUT)) {
      echo FORM_REQUIRED_INPUT;
    }
    ?>
  </div>

<?php
  if (!Text::is_empty(ENTRY_GENDER_TEXT)) {
?>
      <span id="atGender" class="form-text"><small><?= ENTRY_GENDER_TEXT ?></small></span>
<?php
  }

?>
      </div>
    </div>
    