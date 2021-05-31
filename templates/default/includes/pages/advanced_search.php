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

<script src="includes/general.js"></script>
<script><!--
function check_form() {
  var error_message = "<?= JS_ERROR ?>";
  var error_found = false;
  var error_field;
  var keywords = document.advanced_search.keywords.value;
  var dfrom = document.advanced_search.dfrom.value;
  var dto = document.advanced_search.dto.value;
  var pfrom = document.advanced_search.pfrom.value;
  var pto = document.advanced_search.pto.value;
  var pfrom_float;
  var pto_float;

  if ( ((keywords == '') || (keywords.length < 1)) && ((dfrom == '') || (dfrom.length < 1)) && ((dto == '') || (dto.length < 1)) && ((pfrom == '') || (pfrom.length < 1)) && ((pto == '') || (pto.length < 1)) ) {
    error_message = error_message + "* <?= ERROR_AT_LEAST_ONE_INPUT ?>\n";
    error_field = document.advanced_search.keywords;
    error_found = true;
  }

  if (pfrom.length > 0) {
    pfrom_float = parseFloat(pfrom);
    if (isNaN(pfrom_float)) {
      error_message = error_message + "* <?= ERROR_PRICE_FROM_MUST_BE_NUM ?>\n";
      error_field = document.advanced_search.pfrom;
      error_found = true;
    }
  } else {
    pfrom_float = 0;
  }

  if (pto.length > 0) {
    pto_float = parseFloat(pto);
    if (isNaN(pto_float)) {
      error_message = error_message + "* <?= ERROR_PRICE_TO_MUST_BE_NUM ?>\n";
      error_field = document.advanced_search.pto;
      error_found = true;
    }
  } else {
    pto_float = 0;
  }

  if ( (pfrom.length > 0) && (pto.length > 0) ) {
    if ( (!isNaN(pfrom_float)) && (!isNaN(pto_float)) && (pto_float < pfrom_float) ) {
      error_message = error_message + "* <?= ERROR_PRICE_TO_LESS_THAN_PRICE_FROM ?>\n";
      error_field = document.advanced_search.pto;
      error_found = true;
    }
  }

  if (error_found == true) {
    alert(error_message);
    error_field.focus();
    return false;
  } else {
    return true;
  }
}
//--></script>

<h1 class="display-4"><?= HEADING_TITLE_1 ?></h1>

<?php
  if ($messageStack->size('search') > 0) {
    echo $messageStack->output('search');
  }
?>

<?= (new Form('advanced_search', $Linker->build('advanced_search_result.php', [], false), 'get', ['onsubmit' => 'return check_form(this);']))->hide_session_id()->hide('search_in_description', '1') ?>

  <div class="form-group row">
    <label for="inputKeywords" class="col-form-label col-sm-3 text-left text-sm-right"><?= HEADING_SEARCH_CRITERIA ?></label>
    <div class="col-sm-9">
      <?= (new Input('keywords', ['id' => 'inputKeywords', 'placeholder' => TEXT_SEARCH_PLACEHOLDER], 'search'))->require(),
          FORM_REQUIRED_INPUT
      ?>
    </div>
  </div>

  <div class="buttonSet">
    <div class="text-right"><?= new Button(IMAGE_BUTTON_SEARCH, 'fas fa-search', 'btn-success btn-lg btn-block') ?></div>
    <p><a data-toggle="modal" href="#helpSearch" class="btn btn-light"><?= TEXT_SEARCH_HELP_LINK ?></a></p>
  </div>

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
