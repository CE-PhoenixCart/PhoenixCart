<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $parameters = [
    'aria-labelledby' => 'atGenderLabel',
    'class' => 'custom-control-input',
  ];

  if (!Text::is_empty(ENTRY_GENDER_TEXT)) {
    $parameters['aria-describedby'] = 'atGender';
  }
?>
  <div class="form-group row align-items-center">
    <span id="atGenderLabel" class="col-form-label col-sm-3 text-left text-sm-right"><?= ENTRY_GENDER ?></span>
    <div class="col-sm-9">
<?php
  $fieldset_close = null;
  if ($this->is_required()) {
    echo '    <fieldset aria-required="true">' . "\n";
    $fieldset_close = "    </fieldset>\n";
    $parameters['required'] = null;
  }
  $tickable = new Tickable('gender', $parameters, 'radio');
?>
      <div class="custom-control custom-radio custom-control-inline">
        <?= (clone $tickable)->set('value', 'm')->tick('m' === $gender)->set('id', 'genderM') ?>
        <label class="custom-control-label" for="genderM"><?= MALE ?></label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <?= $tickable->set('value', 'f')->tick('f' === $gender)->set('id', 'genderF') ?>
        <label class="custom-control-label" for="genderF"><?= FEMALE ?></label>
      </div>
<?php
  if (isset($fieldset_close)) {
    echo $fieldset_close;
  }

  if (!Text::is_empty(ENTRY_GENDER_TEXT)) {
?>
      <span id="atGender" class="form-text"><small><?= ENTRY_GENDER_TEXT ?></small></span>
<?php
  }

  if ($this->is_required() && !Text::is_empty(FORM_REQUIRED_INPUT)) {
?>
      <div class="float-right"><?= FORM_REQUIRED_INPUT ?></div>
<?php
  }
?>
    </div>
  </div>
