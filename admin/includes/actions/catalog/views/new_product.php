<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($_GET['pID']) && empty($_POST)) {
    $product = product_by_id::administer($_GET['pID']);
    $translations = $product->get('translations');
  } else {
    $product = new Product([
      'products_name' => '',
      'products_description' => '',
      'products_url' => '',
      'products_id' => '',
      'products_quantity' => '',
      'products_model' => '',
      'products_image' => '',
      'products_price' => '',
      'products_weight' => '',
      'products_date_added' => '',
      'products_last_modified' => '',
      'products_date_available' => '',
      'products_status' => '',
      'products_tax_class_id' => '',
      'manufacturers_id' => '',
      'products_gtin' => '',
      'products_seo_description' => '',
      'products_seo_keywords' => '',
      'products_seo_title' => '',
    ]);
  }

  $manufacturers_array = array_merge([['id' => '', 'text' => TEXT_NONE]],
    $db->fetch_all("SELECT manufacturers_id AS id, manufacturers_name AS text FROM manufacturers ORDER BY manufacturers_name"));

  $tax_classes = array_merge([['id' => '0', 'text' => TEXT_NONE]], Tax::fetch_classes());

  $pIn = new Tickable('products_status', ['value' => '1', 'id' => 'pIn', 'class' => 'custom-control-input'], 'radio');
  $pOut = new Tickable('products_status', ['value' => '0', 'id' => 'pOut', 'class' => 'custom-control-input'], 'radio');
  if ('0' === $product->get('status')) {
    $pOut->tick();
  } else {
    $pIn->tick();
  }

  $form_link = $Admin->link('catalog.php', [
    'cPath' => $cPath,
    'action' => isset($_GET['pID']) ? 'update_product' : 'insert_product',
  ]);

  if (isset($_GET['pID'])) {
    $form_link->set_parameter('pID', (int)$_GET['pID']);
  }
?>
<script>
var tax_rates = new Array();
<?php
  foreach (array_column($tax_classes, 'id') as $tax_class_id) {
    if ($tax_class_id > 0) {
      printf(<<<"EOJS"
tax_rates['%s'] = %s;

EOJS
      , "$tax_class_id", Tax::get_rate($tax_class_id));
    }
  }
?>

function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
  var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
  var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}

function updateGross() {
  var taxRate = getTaxRate();
  var grossValue = document.forms["new_product"].products_price.value;

  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
}

function updateNet() {
  var taxRate = getTaxRate();
  var netValue = document.forms["new_product"].products_price_gross.value;

  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price.value = doRound(netValue, 4);
}
</script>

<?= (new Form('new_product', $form_link, 'post', ['enctype' => 'multipart/form-data']))->hide('products_date_added', ($product->get('date_added') ?: date('Y-m-d'))) ?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= (isset($_GET['pID']) ? sprintf(TEXT_EXISTING_PRODUCT, $product->get('name'), Categories::draw_breadcrumbs([$current_category_id])) : sprintf(TEXT_NEW_PRODUCT, Categories::draw_breadcrumbs([$current_category_id]))) ?: TEXT_TOP ?></h1>
    </div>
    <div class="col-1 text-right align-self-center">
      <?= $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $Admin->link('catalog.php')->retain_query_except(['action'])) ?>
    </div>
  </div>

  <div id="productTabs">
    <ul class="nav nav-tabs">
      <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#section_data_content" role="tab"><?= SECTION_HEADING_DATA ?></a></li>
      <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#section_general_content" role="tab"><?= SECTION_HEADING_GENERAL ?></a></li>
      <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#section_images_content" role="tab"><?= SECTION_HEADING_IMAGES ?></a></li>
    </ul>

    <div class="tab-content pt-3">
      <div class="tab-pane fade show active" id="section_data_content" role="tabpanel">
        <div class="form-group row align-items-center" id="zStatus">
          <label class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_STATUS ?></label>
          <div class="col-sm-9">
            <div class="custom-control custom-radio custom-control-inline">
              <?= $pIn, '<label class="custom-control-label" for="pIn">', TEXT_PRODUCT_AVAILABLE, '</label>' ?>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
              <?= $pOut, '<label class="custom-control-label" for="pOut">', TEXT_PRODUCT_NOT_AVAILABLE, '</label>' ?>
            </div>
          </div>
        </div>

        <div class="form-group row" id="zQty">
          <label for="pQty" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_QUANTITY ?></label>
          <div class="col-sm-9">
            <?= (new Input('products_quantity', ['id' => 'pQty']))->require()->default_value($product->get('in_stock') ?? '0')->append_css('form-control w-25') ?>
          </div>
        </div>

        <div class="form-group row" id="zDate">
          <label for="products_date_available" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_DATE_AVAILABLE ?></label>
          <div class="col-sm-9">
            <?= (new Input('products_date_available', ['id' => 'products_date_available', 'aria-describedby' => 'pDateHelp', 'class' => 'form-control w-25']))->default_value($product->get('date_available') ?? '') ?>
            <small id="pDateHelp" class="form-text text-muted">
              <?= TEXT_PRODUCTS_DATE_AVAILABLE_HELP ?>
            </small>
          </div>
        </div>

        <hr>

        <div class="form-group row" id="zBrand">
          <label for="pBrand" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_MANUFACTURER ?></label>
          <div class="col-sm-9">
            <?= (new Select('manufacturers_id', $manufacturers_array, ['id' => 'pBrand']))->set_selection($product->get('manufacturers_id') ?? '') ?>
          </div>
        </div>

        <div class="form-group row" id="zModel">
          <label for="pModel" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_MODEL ?></label>
          <div class="col-sm-9">
            <?= (new Input('products_model', ['id' => 'pModel']))->default_value($product->get('model') ?? '') ?>
          </div>
        </div>

        <hr>

        <div class="form-group row" id="zTax">
          <label for="pTax" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_TAX_CLASS ?></label>
          <div class="col-sm-9">
            <?= (new Select('products_tax_class_id', $tax_classes, ['id' => 'pTax', 'onchange' => 'updateGross()']))->set_selection($product->get('tax_class_id') ?? '') ?>
          </div>
        </div>

        <div class="form-group row" id="zNet">
          <label for="pNet" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_PRICE_NET ?></label>
          <div class="col-sm-9">
            <?= (new Input('products_price', ['id' => 'pNet', 'class' => 'form-control w-25', 'onchange' => 'updateGross()']))->require()->default_value($product->get('price') ?? '') ?>
          </div>
        </div>
        <div class="form-group row" id="zGross">
          <label for="pGross" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_PRICE_GROSS ?></label>
          <div class="col-sm-9">
            <?= (new Input('products_price_gross', ['id' => 'pGross', 'class' => 'form-control w-25', 'onchange' => 'updateNet()']))->default_value($product->get('price') ?? '') ?>
          </div>
        </div>

        <hr>

        <div class="form-group row" id="zWeight">
          <label for="pWeight" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_WEIGHT ?></label>
          <div class="col-sm-9">
            <?= (new Input('products_weight', ['id' => 'pWeight', 'class' => 'form-control w-25']))->default_value($product->get('weight') ?? '') ?>
          </div>
        </div>

        <div class="form-group row" id="zGtin">
          <label for="pGtin" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_GTIN ?></label>
          <div class="col-sm-9">
            <?= (new Input('products_gtin', ['id' => 'pGtin', 'class' => 'form-control w-25', 'aria-describedby' => 'pGtinHelp']))->default_value($product->get('gtin') ?? '') ?>
            <small id="pGtinHelp" class="form-text text-muted">
            <?= TEXT_PRODUCTS_GTIN_HELP ?>
            </small>
          </div>
        </div>

        <?= $admin_hooks->cat('injectDataForm') ?>

      </div>

      <div class="tab-pane fade" id="section_general_content" role="tabpanel">
        <div class="accordion" id="productLanguageAccordion">
          <?php
          $show = ' show';
          foreach (language::load_all() as $l) {
            ?>
            <div class="card">
              <div class="card-header" id="heading<?= $l['directory'] ?>">
                <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#<?= $l['directory'] ?>" aria-expanded="true" aria-controls="<?= $l['directory'] ?>"><?= $Admin->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", ['class' => 'lng mr-2'], $l['name']) . $l['name'] ?></button>
              </div>
              <div id="<?= $l['directory'] ?>" class="collapse<?= $show ?>" aria-labelledby="heading<?= $l['directory'] ?>" data-parent="#productLanguageAccordion">
                <div class="card-body">
                  <div class="form-group row" id="zName<?= $l['directory'] ?>">
                    <label for="pName" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_NAME ?></label>
                    <div class="col-sm-9">
                      <?= (new Input("products_name[{$l['id']}]", ['id' => 'pName']))->require()->default_value($translations[$l['id']]['name'] ?? '') ?>
                    </div>
                  </div>

                  <div class="form-group row" id="zDesc<?= $l['directory'] ?>">
                    <label for="pDesc" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_DESCRIPTION ?></label>
                    <div class="col-sm-9">
                      <?= (new Textarea("products_description[{$l['id']}]", ['id' => 'pDesc', 'cols' => '70', 'rows' => '15']))->require()->set_text($translations[$l['id']]['description'] ?? '') ?>
                    </div>
                  </div>

                  <div class="form-group row" id="zUrl<?= $l['directory'] ?>">
                    <label for="pUrl" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_URL ?></label>
                    <div class="col-sm-9">
                      <?= (new Input("products_url[{$l['id']}]", ['id' => 'pUrl', 'aria-describedby' => 'pUrlHelp']))->default_value($translations[$l['id']]['url'] ?? '') ?>
                      <small id="pUrlHelp" class="form-text text-muted">
                        <?= TEXT_PRODUCTS_URL_WITHOUT_HTTP ?>
                      </small>
                    </div>
                  </div>

                  <div class="form-group row" id="zSeoTitle<?= $l['directory'] ?>">
                    <label for="pSeoTitle" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_SEO_TITLE ?></label>
                    <div class="col-sm-9">
                      <?= (new Input("products_seo_title[{$l['id']}]", ['id' => 'pSeoTitle', 'aria-describedby' => 'pSeoTitleHelp']))->default_value($translations[$l['id']]['seo_title'] ?? '') ?>
                      <small id="pSeoTitleHelp" class="form-text text-muted">
                        <?= TEXT_PRODUCTS_SEO_TITLE_HELP ?>
                      </small>
                    </div>
                  </div>

                  <div class="form-group row" id="zSeoDesc<?= $l['directory'] ?>">
                    <label for="pSeoDesc" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_SEO_DESCRIPTION ?></label>
                    <div class="col-sm-9">
                      <?= (new Textarea("products_seo_description[{$l['id']}]", [
                             'id' => 'pSeoDesc',
                             'aria-describedby' => 'pSeoDescHelp',
                             'cols' => '70',
                             'rows' => '15',
                           ]))->set_text($translations[$l['id']]['seo_description'] ?? '') ?>
                      <small id="pSeoDescHelp" class="form-text text-muted">
                        <?= TEXT_PRODUCTS_SEO_DESCRIPTION_HELP ?>
                      </small>
                    </div>
                  </div>

                  <div class="form-group row" id="zSeoKeywords<?= $l['directory'] ?>">
                    <label for="pSeoKeywords" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_SEO_KEYWORDS ?></label>
                    <div class="col-sm-9">
                      <?= (new Input('products_seo_keywords[' . $l['id'] . ']', [
                             'id' => 'pSeoKeywords',
                             'placeholder' => PLACEHOLDER_COMMA_SEPARATION,
                             'aria-describedby' => 'pSeoKeywordsHelp',
                           ]))->default_value($translations[$l['id']]['seo_keywords'] ?? '') ?>
                      <small id="pSeoKeywordsHelp" class="form-text text-muted">
                        <?= TEXT_PRODUCTS_SEO_KEYWORDS_HELP ?>
                      </small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php
            echo $admin_hooks->cat('injectLanguageForm');

            if ('' !== $show) {
              $show = '';
            }
          }

          $image_input = new Input('products_image', ['id' => 'pImg', 'class' => 'custom-file-input'], 'file');
          if (Text::is_empty($product->get('image'))) {
            $image_input->require();
          }
          ?>
        </div>
      </div>

      <div class="tab-pane fade" id="section_images_content" role="tabpanel">
        <div class="mb-3">
          <div class="form-group row" id="zImg">
            <label for="pImg" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PRODUCTS_MAIN_IMAGE ?></label>
            <div class="col-sm-9">
              <div class="custom-file mb-2">
                <?=
                $image_input,
                '<label class="custom-file-label" for="pImg">', $product->get('image'), '</label>'
                ?>
              </div>
            </div>
          </div>

          <hr>

          <div class="form-group row" id="zPiList">
            <div class="col-sm-3 text-left text-sm-right">
              <?= TEXT_PRODUCTS_OTHER_IMAGES ?>
              <br><a class="btn btn-info btn-sm text-white mt-2" role="button" href="#" id="add_image" onclick="addNewPiForm();return false;"><?= TEXT_PRODUCTS_ADD_LARGE_IMAGE ?></a>
            </div>
            <div class="col-sm-9" id="piList">
              <?php
              $pi_counter = 0;
              foreach ($product->get('images') as $pi) {
                $pi_counter++;
                echo '<div class="row mb-2" id="piId' . $pi_counter . '">';
                  echo '<div class="col">';
                    echo '<div class="custom-file mb-2">';
                      echo (new Input('products_image_large_' . $pi['id'], ['id' => "pImg$pi_counter", 'class' => 'custom-file-input'], 'file'));
                      echo '<label class="custom-file-label" for="pImg' . $pi_counter . '">' . $pi['image'] . '</label>';
                    echo '</div>';
                  echo '</div>';
                  echo '<div class="col">';
                    echo (new Textarea('products_image_htmlcontent_' . $pi['id'], ['cols' => '70', 'rows' => '3']))->set_text($pi['htmlcontent']);
                    echo '<small class="form-text text-muted">' . TEXT_PRODUCTS_LARGE_IMAGE_HTML_CONTENT . '</small>';
                  echo '</div>';
                   echo '<div class="col-1">';
                     echo '<i class="fas fa-arrows-alt-v mr-2"></i>';
                     echo '<a href="#" class="piDel" data-pi-id="' . $pi_counter . '"><i class="fas fa-trash text-danger"></i></a>';
                  echo '</div>';
                echo '</div>';
              }
              ?>
            </div>
          </div>

          <?= $admin_hooks->cat('injectImageForm') ?>

          <script>
          $('#piList').sortable({ containment: 'parent' });

          var piSize = <?= $pi_counter ?>;

          function addNewPiForm() {
            piSize++;

            $('#piList').append('<div class="row mb-2" id="piId' + piSize + '"><div class="col"><div class="custom-file mb-2"><input type="file" class="custom-file-input" id="pImg' + piSize + '" name="products_image_large_new_' + piSize + '"><label class="custom-file-label" for="pImg' + piSize + '">&nbsp;</label></div></div><div class="col"><textarea name="products_image_htmlcontent_new_' + piSize + '" wrap="soft" class="form-control" cols="70" rows="3"></textarea><small class="form-text text-muted"><?= TEXT_PRODUCTS_LARGE_IMAGE_HTML_CONTENT ?></small></div><div class="col-1"><i class="fas fa-arrows-alt-v mr-2"></i><a class="piDel" data-pi-id="' + piSize + '"><i class="fas fa-trash text-danger"></i></a></div></div>');
          }

          $('a.piDel').click(function(e){
            var p = $(this).data('pi-id');
            $('#piId' + p).effect('blind').remove();

            e.preventDefault();
          });
          </script>
        </div>
      </div>

      <?= $admin_hooks->cat('productTab') ?>
    </div>
  </div>

  <script>
  updateGross();
  $('#products_date_available').datepicker({ dateFormat: 'yy-mm-dd' });
  </script>

  <?=
  new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success btn-block btn-lg mt-3 mb-1'),
  $Admin->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $Admin->link('catalog.php')->retain_query_except(['action']))
  ?>

</form>
