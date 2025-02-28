<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $page = info_pages::get_page(['pd.pages_id' => (int)$_GET['pID']]);

  $pInfo = new objectInfo($page);

  if (!isset($pInfo->pages_status)) $pInfo->pages_status = '1';
  $out_status = '0' == $pInfo->pages_status;
  $in_status = !$out_status;

  $form = new Form('pages', $link->set_parameter('pID', (int)$_GET['pID'])->set_parameter('action', 'update'), 'post', ['enctype' => 'multipart/form-data']);
  $form->hide('pages_id', (int)$pInfo->pages_id)
       ->hide('date_added', $pInfo->date_added);

  echo $form;
?>

    <div class="row mb-2 align-items-center" id="zStatus">
      <div class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_PAGE_STATUS ?></div>
      <div class="col-sm-9">
        <div class="form-check form-check-inline">
          <?= (new Tickable('page_status', ['value' => '1', 'id' => 'inStatus', 'class' => 'form-check-input'], 'radio'))->tick($in_status) ?>
          <label class="form-check-label" for="inStatus"><?= TEXT_PAGE_PUBLISHED ?></label>
        </div>
        <div class="form-check form-check-inline">
          <?= (new Tickable('page_status', ['value' => '0', 'id' => 'outStatus', 'class' => 'form-check-input'], 'radio'))->tick($out_status) ?>
          <label class="form-check-label" for="outStatus"><?= TEXT_PAGE_NOT_PUBLISHED ?></label>
        </div>
      </div>
    </div>

<?php
    $page_title = $page_text = $navbar_title = '';

    foreach (language::load_all() as $l) {
      $flag_image = $Admin->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", ['alt' => $l['name']]);
      $info_page = info_pages::get_page(['pd.pages_id' => (int)$pInfo->pages_id, 'pd.languages_id' => (int)$l['id']]);

      $navbar_title .= '<div class="input-group mb-1">';
        $navbar_title .= '<span class="input-group-text">' . $flag_image . '</span>';
        $navbar_title .= (new Input("navbar_title[{$l['id']}]", ['id' => "iNavbarTitle-{$l['code']}", 'value' => $info_page['navbar_title']]))->require();
      $navbar_title .= '</div>';

      $page_title .= '<div class="input-group mb-1">';
        $page_title .= '<span class="input-group-text">' . $flag_image . '</span>';
        $page_title .= (new Input("page_title[{$l['id']}]", ['id' => "iPageTitle-{$l['code']}", 'value' => $info_page['pages_title']]))->require();
      $page_title .= '</div>';

      $page_text .= '<div class="input-group mb-1">';
        $page_text .= '<span class="input-group-text">' . $flag_image . '</span>';
        $page_text .= (new Textarea("page_text[{$l['id']}]", ['id' => "iPageText-{$l['code']}", 'cols' => '80', 'rows' => '10', 'class' => 'form-control editor']))->require()->set_text($info_page['pages_text'] ?? '');
      $page_text .= '</div>';
    }
?>

    <div class="row mb-2" id="zNavbarTitle">
      <div class="col-form-label col-sm-3 text-start text-sm-end"><?= NAVBAR_TITLE ?></div>
      <div class="col-sm-9"><?= $navbar_title ?></div>
    </div>

    <hr>

    <div class="row mb-2" id="zPageTitle">
      <div class="col-form-label col-sm-3 text-start text-sm-end"><?= PAGE_TITLE ?></div>
      <div class="col-sm-9"><?= $page_title ?></div>
    </div>

    <div class="row mb-2" id="zPageText">
      <div class="col-form-label col-sm-3 text-start text-sm-end"><?= PAGE_TEXT ?></div>
      <div class="col-sm-9"><?= $page_text ?></div>
    </div>

    <hr>

    <div class="row mb-2" id="zInputSlug">
      <label for="inputSlug" class="col-form-label col-sm-3 text-start text-sm-end"><?= PAGE_SLUG ?></label>
      <div class="col-sm-9">
        <?= (new Input('slug', ['value' => $pInfo->slug, 'id' => 'inputSlug', 'class' => 'form-control w-50']))->require() ?>
      </div>
    </div>

    <div class="row mb-2" id="zInputSort">
      <label for="inputSort" class="col-form-label col-sm-3 text-start text-sm-end"><?= SORT_ORDER ?></label>
      <div class="col-sm-9">
        <?= (new Input('sort_order', ['value' => $pInfo->sort_order, 'id' => 'inputSort', 'class' => 'form-control w-50']))->require() ?>
      </div>
    </div>

    <?= $admin_hooks->cat('formEdit') ?>
    
    <div class="d-grid mt-2">
      <?= new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success btn-lg') ?>
    </div>


  </form>
