<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  echo new Form('pages', $Admin->link()->set_parameter('action', 'add_new'), 'post', ['enctype' => 'multipart/form-data']);
?>

    <div class="row mb-2 align-items-center" id="zStatus">
      <div class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_PAGE_STATUS ?></div>
      <div class="col-sm-9">
        <div class="form-check form-check-inline">
          <?= new Tickable('page_status', ['value' => '1', 'id' => 'inStatus', 'class' => 'form-check-input'], 'radio') ?>
          <label class="form-check-label" for="inStatus"><?= TEXT_PAGE_PUBLISHED ?></label>
        </div>
        <div class="form-check form-check-inline">
          <?= (new Tickable('page_status', ['value' => '0', 'id' => 'outStatus', 'class' => 'form-check-input'], 'radio'))->tick() ?>
          <label class="form-check-label" for="outStatus"><?= TEXT_PAGE_NOT_PUBLISHED ?></label>
        </div>
      </div>
    </div>

<?php
    $page_title = $page_text = $navbar_title = '';

    foreach (language::load_all() as $l) {
      $flag_image = $Admin->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", ['alt' => $l['name']]);

      $navbar_title .= '<div class="input-group mb-1">';
        $navbar_title .= '<span class="input-group-text">' . $flag_image . '</span>';
        $navbar_title .= (new Input("navbar_title[{$l['id']}]", ['id' => "iNavbarTitle-{$l['code']}"]))->require();
      $navbar_title .= '</div>';

      $page_title .= '<div class="input-group mb-1">';
        $page_title .= '<span class="input-group-text">' . $flag_image . '</span>';
        $page_title .= (new Input("page_title[{$l['id']}]", ['id' => "iPageTitle-{$l['code']}"]))->require();
      $page_title .= '</div>';

      $page_text .= '<div class="input-group mb-1">';
        $page_text .= '<span class="input-group-text">' . $flag_image . '</span>';
        $page_text .= (new Textarea("page_text[{$l['id']}]", ['id' => "iPageText-{$l['code']}", 'cols' => '80', 'rows' => '10', 'class' => 'form-control editor']))->require();
      $page_text .= '</div>';
    }
?>

    <div class="row mb-2" id="zNavbarTitle">
      <div class="col-form-label col-sm-3 text-start text-sm-end"><?= NAVBAR_TITLE ?></div>
      <div class="col-sm-9">
        <?= $navbar_title ?>
      </div>
    </div>

    <hr>

    <div class="row mb-2" id="zPageTitle">
      <div class="col-form-label col-sm-3 text-start text-sm-end"><?= PAGE_TITLE ?></div>
      <div class="col-sm-9">
        <?= $page_title ?>
      </div>
    </div>

    <div class="row mb-2" id="zPageText">
      <div class="col-form-label col-sm-3 text-start text-sm-end"><?= PAGE_TEXT ?></div>
      <div class="col-sm-9">
        <?= $page_text ?>
      </div>
    </div>

    <hr>

    <div class="row mb-2" id="zInputSlug">
      <label for="inputSlug" class="col-form-label col-sm-3 text-start text-sm-end"><?= PAGE_SLUG ?></label>
      <div class="col-sm-9">
        <?= (new Input('slug', ['id' => 'inputSlug', 'class' => 'form-control w-50', 'aria-describedby' => 'zSlugHelp']))->require() ?>
        <small id="zSlugHelp" class="form-text text-muted"><?= TEXT_PAGE_SLUG_HELP ?></small>
      </div>
    </div>

    <div class="row mb-2" id="zSortOrder">
      <label for="inputSort" class="col-form-label col-sm-3 text-start text-sm-end"><?= SORT_ORDER ?></label>
      <div class="col-sm-9">
        <?= (new Input('sort_order', ['id' => 'inputSort', 'class' => 'form-control w-50']))->require() ?>
      </div>
    </div>

    <?= $admin_hooks->cat('formNew') ?>
    
    <div class="d-grid mt-2">
      <?= new Button(IMAGE_SAVE, 'fas fa-pen', 'btn-success btn-lg') ?>
    </div>

  </form>
