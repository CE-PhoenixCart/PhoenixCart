<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $aID = Text::input($_GET['aID']);

  $advert_query = $db->query(sprintf(<<<'EOSQL'
SELECT a.*, ai.*
 FROM advert a INNER JOIN advert_info ai ON a.advert_id = ai.advert_id
 WHERE a.advert_id = %d
EOSQL
    , (int)$aID));
  $advert = $advert_query->fetch_assoc();

  $aInfo = new objectInfo($advert);
  $link->set_parameter('aID', (string)(int)$aID);

  $groups_array = $db->fetch_all("SELECT DISTINCT advert_group AS id, advert_group AS text FROM advert ORDER BY advert_group");

  $form = new Form('edit_advert', $link->set_parameter('action', 'update'), 'post', ['enctype' => 'multipart/form-data']);
  $form->hide('advert_id', $aInfo->advert_id);
?>

  <?= $form ?>

    <div class="row mb-2" id="zTitle">
      <label for="aTitle" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_ADVERT_TITLE ?></label>
      <div class="col-sm-9">
        <?= (new Input('advert_title', ['value' => $aInfo->advert_title, 'id' => 'aTitle', 'aria-describedby' => 'aTitleHelp']))->require() ?>
        <small id="aTitleHelp" class="form-text text-muted"><?= TEXT_ADVERT_TITLE_HELP ?></small>
      </div>
    </div>

    <div class="row mb-2" id="zUrlFrag">
      <label for="aUrl" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_ADVERT_URL ?></label>
      <div class="col-sm-9">
        <div class="row">
          <div class="col">
            <?= new Input('advert_url', ['value' => $aInfo->advert_url ?? '', 'id' => 'aUrl', 'aria-describedby' => 'aUrlHelp']) ?>
            <small id="aUrlHelp" class="form-text text-muted"><?= TEXT_ADVERT_URL_HELP ?></small>
          </div>
          <div class="col">
            <?= new Input('advert_fragment', ['value' => $aInfo->advert_fragment ?? '', 'id' => 'aFrag', 'aria-describedby' => 'aFragHelp']) ?>
            <small id="aFragHelp" class="form-text text-muted"><?= TEXT_ADVERT_FRAGMENT_HELP ?></small>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-2" id="zSort">
      <label for="aSort" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_ADVERT_SORT_ORDER ?></label>
      <div class="col-sm-9">
        <?= new Input('sort_order', ['value' => $aInfo->sort_order ?? 0, 'id' => 'aSort', 'class' => 'form-control w-25', 'aria-describedby' => 'aSortHelp']) ?>
        <small id="aSortHelp" class="form-text text-muted"><?= TEXT_ADVERT_SORT_HELP ?></small>
      </div>
    </div>

    <hr>

    <div class="row mb-2" id="zGroup">
      <label for="aGroup" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_ADVERT_GROUP ?></label>
      <div class="col-sm-9">
        <div class="row">
          <div class="col">
           <?= (new Select('advert_group', $groups_array, ['class' => 'form-select', 'id' => 'aGroup']))->set_selection($aInfo->advert_group ?? '') ?>
          </div>
          <div class="col">
            <?= new Input('new_advert_group', ['id' => 'aNewGroup', 'placeholder' => TEXT_ADVERT_NEW_GROUP, 'aria-describedby' => 'aGroupHelp']) ?>
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
            <?= new Input('advert_image_local', ['value' => $aInfo->advert_image ?? '', 'id' => 'aNewImage', 'placeholder' => TEXT_ADVERT_IMAGE_LOCAL]) ?>
          </div>
          <div class="col">
            <?= new Input('advert_image_target', ['id' => 'aTarget', 'placeholder' => TEXT_ADVERT_IMAGE_TARGET]) ?>
          </div>
        </div>
      </div>
    </div>

    <hr>

    <?php
    foreach (language::load_all() as $l) {
      $advert_text = adverts::advert_get_html_text($aInfo->advert_id, $l['id']) ?? '';
      $language_icon = $Admin->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", [], $l['name']);
      ?>
      <div class="row mb-2" id="zText_<?= $l['code'] ?>">
        <label for="aText-<?= $l['code'] ?>" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_ADVERT_HTML_TEXT ?></label>
        <div class="col-sm-9">
          <div class="input-group">
            <span class="input-group-text"><?= $language_icon ?></span>
            <?= (new Textarea('advert_html_text[' . $l['id'] . ']', ['cols' => '60', 'rows' => '15', 'id' => "aText-{$l['code']}"]))->set_text($advert_text ?? '') ?>
          </div>
        </div>
      </div>
      <?php
    }

    echo $admin_hooks->cat('editForm');
    ?>
    
    <div class="d-grid mt-2">
      <?= new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success btn-lg') ?>
    </div>

  </form>
