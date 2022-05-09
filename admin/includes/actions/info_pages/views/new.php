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

    <div class="form-group row align-items-center" id="zStatus">
      <label class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PAGE_STATUS ?></label>
      <div class="col-sm-9">
        <div class="custom-control custom-radio custom-control-inline">
          <?= new Tickable('page_status', ['value' => '1', 'id' => 'inStatus', 'class' => 'custom-control-input'], 'radio') ?>
          <label class="custom-control-label" for="inStatus"><?= TEXT_PAGE_PUBLISHED ?></label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
          <?= (new Tickable('page_status', ['value' => '0', 'id' => 'outStatus', 'class' => 'custom-control-input'], 'radio'))->tick() ?>
          <label class="custom-control-label" for="outStatus"><?= TEXT_PAGE_NOT_PUBLISHED ?></label>
        </div>
      </div>
    </div>

<?php
    $page_title = $page_text = $navbar_title = '';

    foreach (language::load_all() as $l) {
      $flag_image = $Admin->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", ['alt' => $l['name']]);

      $navbar_title .= '<div class="input-group mb-1">';
        $navbar_title .= '<div class="input-group-prepend">';
          $navbar_title .= '<span class="input-group-text">' . $flag_image . '</span>';
        $navbar_title .= '</div>';
        $navbar_title .= (new Input("navbar_title[{$l['id']}]"))->require();
      $navbar_title .= '</div>';

      $page_title .= '<div class="input-group mb-1">';
        $page_title .= '<div class="input-group-prepend">';
          $page_title .= '<span class="input-group-text">' . $flag_image . '</span>';
        $page_title .= '</div>';
        $page_title .= (new Input("page_title[{$l['id']}]"))->require();
      $page_title .= '</div>';

      $page_text .= '<div class="input-group mb-1">';
        $page_text .= '<div class="input-group-prepend">';
          $page_text .= '<span class="input-group-text">' . $flag_image . '</span>';
        $page_text .= '</div>';
        $page_text .= (new Textarea("page_text[{$l['id']}]", ['cols' => '80', 'rows' => '10', 'class' => 'form-control editor']))->require();
      $page_text .= '</div>';
    }
?>

    <div class="form-group row" id="zNavbarTitle">
      <label class="col-form-label col-sm-3 text-left text-sm-right"><?= NAVBAR_TITLE ?></label>
      <div class="col-sm-9">
        <?= $navbar_title ?>
      </div>
    </div>

    <hr>

    <div class="form-group row" id="zPageTitle">
      <label class="col-form-label col-sm-3 text-left text-sm-right"><?= PAGE_TITLE ?></label>
      <div class="col-sm-9">
        <?= $page_title ?>
      </div>
    </div>

    <div class="form-group row" id="zPageText">
      <label class="col-form-label col-sm-3 text-left text-sm-right"><?= PAGE_TEXT ?></label>
      <div class="col-sm-9">
        <?= $page_text ?>
      </div>
    </div>

    <hr>

    <div class="form-group row" id="zInputSlug">
      <label for="inputSlug" class="col-form-label col-sm-3 text-left text-sm-right"><?= PAGE_SLUG ?></label>
      <div class="col-sm-9">
        <?= (new Input('slug', ['id' => 'inputSlug', 'class' => 'form-control w-50']))->require() ?>
      </div>
    </div>

    <div class="form-group row" id="zSortOrder">
      <label for="inputSort" class="col-form-label col-sm-3 text-left text-sm-right"><?= SORT_ORDER ?></label>
      <div class="col-sm-9">
        <?= (new Input('sort_order', ['id' => 'inputSort', 'class' => 'form-control w-50']))->require() ?>
      </div>
    </div>

    <?=
      $admin_hooks->cat('formNew'),
      new Button(IMAGE_SAVE, 'fas fa-pen', 'btn-success btn-block btn-lg')
    ?>

  </form>
