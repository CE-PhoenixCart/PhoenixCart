<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('advanced_search.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE_1 ?></h1>

<?php
  if ($messageStack->size('search') > 0) {
    echo $messageStack->output('search');
  }
?>

<?= (new Form('advanced_search', $Linker->build('advanced_search_result.php', [], false), 'get'))->hide_session_id()->hide('search_in_description', '1') ?>

  <div class="form-group row">
    <label for="inputKeywords" class="col-form-label col-sm-3 text-left text-sm-right"><?= HEADING_SEARCH_CRITERIA ?></label>
    <div class="col-sm-9">
      <?= (new Input('keywords', ['id' => 'inputKeywords', 'placeholder' => TEXT_SEARCH_PLACEHOLDER], 'search'))->require(),
          FORM_REQUIRED_INPUT
      ?>
    </div>
  </div>

  <p><?= new Button(IMAGE_BUTTON_SEARCH, 'fas fa-search', 'btn-success btn-lg btn-block') ?></p>
  <p><a data-toggle="modal" href="#helpSearch" class="btn btn-light"><?= TEXT_SEARCH_HELP_LINK ?></a></p>

  <div class="modal fade" id="helpSearch" tabindex="-1" role="dialog" aria-labelledby="helpSearchLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><?= HEADING_SEARCH_HELP ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="fas fa-times"></span>
          </button>
        </div>
        <div class="modal-body">
          <p><?= TEXT_SEARCH_HELP ?></p>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <div class="form-group row">
    <label for="entryCategories" class="col-form-label col-sm-3 text-left text-sm-right"><?= ENTRY_CATEGORIES ?></label>
    <div class="col-sm-9">
      <?= new Select('categories_id', Guarantor::ensure_global('category_tree')->get_selections([['id' => '', 'text' => TEXT_ALL_CATEGORIES]]), ['id' => 'entryCategories']) ?>
    </div>
  </div>
  <div class="form-group row">
    <label for="entryIncludeSubs" class="col-form-label col-sm-3 text-left text-sm-right"><?= ENTRY_INCLUDE_SUBCATEGORIES ?></label>
    <div class="col-sm-9">
      <div class="checkbox">
        <label>
          <?= (new Tickable('inc_subcat', ['value' => '1', 'id' => 'entryIncludeSubs'], 'checkbox'))->tick() ?>
        </label>
      </div>
    </div>
  </div>
  <div class="form-group row">
    <label for="entryManufacturers" class="col-form-label col-sm-3 text-left text-sm-right"><?= ENTRY_MANUFACTURERS ?></label>
    <div class="col-sm-9">
      <?= new Select('manufacturers_id', array_merge(
        [['id' => '', 'text' => TEXT_ALL_MANUFACTURERS]],
        $db->fetch_all("SELECT manufacturers_id AS id, manufacturers_name AS text FROM manufacturers ORDER BY manufacturers_name")
        ), ['id' => 'entryManufacturers'])
      ?>
    </div>
  </div>

  <hr>

  <div class="row">
    <label for="PriceTo" class="col-form-label col-sm-3 text-left text-sm-right"><?= ENTRY_PRICE ?></label>
    <div class="col">
      <?= new Input('pfrom', ['id' => 'PriceFrom', 'placeholder' => ENTRY_PRICE_FROM_TEXT]) ?>
    </div>
    <div class="col">
      <?= new Input('pto', ['id' => 'PriceTo', 'placeholder' => ENTRY_PRICE_TO_TEXT]) ?>
    </div>
  </div>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
