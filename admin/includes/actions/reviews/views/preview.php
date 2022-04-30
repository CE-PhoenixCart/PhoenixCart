<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $link->set_parameter('rID', (int)$_GET['rID']);

  if ([] === $_POST) {
    $rID = Text::input($_GET['rID']);

    $rInfo = $db->query(sprintf(<<<'EOSQL'
SELECT r.*, rd.*, p.products_image, pd.products_name
 FROM reviews r
   INNER JOIN reviews_description rd ON r.reviews_id = rd.reviews_id
   LEFT JOIN products p ON p.products_id = r.products_id
   LEFT JOIN products_description pd ON r.products_id = pd.products_id AND pd.language_id = %1$d
 WHERE r.reviews_id = %2$d
 ORDER BY rd.languages_id = %1$d DESC
 LIMIT 1
EOSQL
      , (int)$_SESSION['languages_id'], (int)$rID))->fetch_object();

    $button = $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', isset($_GET['origin']) ? $Admin->link($_GET['origin']) : $link);
  } else {
    $rInfo = new objectInfo($_POST);

    $button = new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success mr-2')
            . $Admin->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link);

    $form = new Form('update', (clone $link)->set_parameter('action', 'update'), 'post', ['enctype' => 'multipart/form-data']);
/* Re-Post all POST'ed variables */
    foreach($_POST as $key => $value) {
      $form->hide($key, htmlspecialchars($value));
    }
    echo $form;
  }
?>
    <div class="row">
      <div class="col-sm-10">
        <div class="form-group row" id="zProduct">
          <label for="reviewProduct" class="col-sm-3 text-left text-sm-right"><?= ENTRY_PRODUCT ?></label>
          <div class="col-sm-9"><?= $rInfo->products_name ?? '' ?></div>
        </div>

        <div class="form-group row" id="zCustomer">
          <label for="reviewCustomer" class="col-sm-3 text-left text-sm-right"><?= ENTRY_FROM ?></label>
          <div class="col-sm-9"><?= $rInfo->customers_name ?></div>
        </div>

        <div class="form-group row" id="zDate">
          <label for="reviewDate" class="col-sm-3 text-left text-sm-right"><?= ENTRY_DATE ?></label>
          <div class="col-sm-9"><?= Date::abridge($rInfo->date_added) ?></div>
        </div>

        <div class="form-group row" id="zRating">
          <label for="reviewRating" class="col-sm-3 text-left text-sm-right"><?= ENTRY_RATING ?></label>
          <div class="col-sm-9"><?= new star_rating((float)$rInfo->reviews_rating) ?></div>
        </div>

        <div class="form-group row" id="zReview">
          <label for="reviewReview" class="col-sm-3 text-left text-sm-right"><?= ENTRY_REVIEW ?></label>
          <div class="col-sm-9"><?= $rInfo->reviews_text ?></div>
        </div>

        <?= $admin_hooks->cat('formPreview'); ?>
      </div>
      <div class="col-sm-2 text-right"><?= $Admin->catalog_image('images/' . $rInfo->products_image ?? '', [], $rInfo->products_name ?? '', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) ?></div>
    </div>

    <div class="text-right">
      <?= $button ?>
    </div>
<?php
    if (isset($form)) {
?>
  </form>
<?php
    }
?>
