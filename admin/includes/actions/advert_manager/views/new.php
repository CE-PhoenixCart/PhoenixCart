<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $groups_array = $db->fetch_all("SELECT DISTINCT advert_group AS id, advert_group AS text FROM advert ORDER BY advert_group");

  echo new Form('new_advert', $Admin->link('advert_manager.php', ['action' => 'add_new']), 'post', ['enctype' => 'multipart/form-data']);
?>

    <div class="row mb-2" id="zTitle">
      <label for="aTitle" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_ADVERT_TITLE ?></label>
      <div class="col-sm-9">
        <?= (new Input('advert_title', ['id' => 'aTitle', 'aria-describedby' => 'aTitleHelp']))->require() ?>
        <small id="aTitleHelp" class="form-text text-muted"><?= TEXT_ADVERT_TITLE_HELP ?></small>
      </div>
    </div>

    <div class="row mb-2" id="zUrlFrag">
      <label for="aUrl" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_ADVERT_URL ?></label>
      <div class="col-sm-9">
        <div class="row">
          <div class="col">
            <?= (new Input('advert_url', ['id' => 'aUrl', 'aria-describedby' => 'aUrlHelp'])) ?>
            <small id="aUrlHelp" class="form-text text-muted"><?= TEXT_ADVERT_URL_HELP ?></small>
          </div>
          <div class="col">
            <?= (new Input('advert_fragment', ['id' => 'aFrag', 'aria-describedby' => 'aFragHelp'])) ?>
            <small id="aFragHelp" class="form-text text-muted"><?= TEXT_ADVERT_FRAGMENT_HELP ?></small>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-2" id="zSort">
      <label for="aSort" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_ADVERT_SORT_ORDER ?></label>
      <div class="col-sm-9">
        <?= (new Input('sort_order', ['id' => 'aSort', 'class' => 'form-control w-25', 'aria-describedby' => 'aSortHelp'])) ?>
        <small id="aSortHelp" class="form-text text-muted"><?= TEXT_ADVERT_SORT_HELP ?></small>
      </div>
    </div>

    <hr>

    <div class="row mb-2" id="zGroup">
      <label for="aGroup" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_ADVERT_GROUP ?></label>
      <div class="col-sm-9">
        <div class="row">
          <div class="col">
           <?= (new Select('advert_group', $groups_array, ['class' => 'form-select', 'id' => 'aGroup'])) ?>
          </div>
          <div class="col">
            <?= (new Input('new_advert_group', ['id' => 'aNewGroup', 'placeholder' => TEXT_ADVERT_NEW_GROUP, 'aria-describedby' => 'aGroupHelp'])) ?>
          </div>
        </div>
      </div>
    </div>

    <hr>

    <div class="row mb-2" id="zImage">
      <div class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_ADVERT_IMAGE ?></div>
      <div class="col-sm-9">
        <div class="row">
          <div class="col">
            <?= new Input('advert_image', ['accept' => 'image/*', 'id' => 'advert_image', 'class' => 'form-control'], 'file') ?>
            <label class="form-label" for="advert_image"></label>
          </div>
          <div class="col">
            <?= (new Input('advert_image_local', ['id' => 'aNewImage', 'placeholder' => TEXT_ADVERT_IMAGE_LOCAL])) ?>
          </div>
          <div class="col">
            <?= (new Input('advert_image_target', ['id' => 'aTarget', 'placeholder' => TEXT_ADVERT_IMAGE_TARGET])) ?>
          </div>
        </div>
      </div>
    </div>

    <hr>

    <?php
    foreach (language::load_all() as $l) {
      $language_icon = $Admin->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", [], $l['name']);
      ?>
      <div class="row mb-2" id="zText_<?= $l['code'] ?>">
        <label for="aText-<?= $l['code'] ?>" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_ADVERT_HTML_TEXT ?></label>
        <div class="col-sm-9">
          <div class="input-group">
            <span class="input-group-text"><?= $language_icon ?></span>
            <?= (new Textarea('advert_html_text[' . $l['id'] . ']', ['cols' => '60', 'rows' => '15', 'id' => "aText-{$l['code']}"])) ?>
          </div>
        </div>
      </div>
      <?php
    }
    ?>

    <div class="alert alert-info">
      <?= TEXT_ADVERT_NOTE . TEXT_INSERT_NOTE ?>
    </div>

    <?= $admin_hooks->cat('newForm') ?>
    
    <div class="d-grid mt-2">
      <?= new Button(IMAGE_SAVE, 'fas fa-images', 'btn-success btn-lg') ?>
    </div>

  </form>
