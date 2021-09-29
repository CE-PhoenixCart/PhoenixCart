<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['new', 'edit', 'set_flag'];
  require 'includes/application_top.php';

  Guarantor::ensure_global('currencies');

  require 'includes/segments/process_action.php';

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col text-right align-self-center">
      <?=
      empty($action)
      ? $Admin->button(BUTTON_INSERT_SPECIAL, 'fas fa-funnel-dollar', 'btn-danger', $Admin->link('specials.php', ['action' => 'new']))
      : $Admin->button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-light mt-2', $Admin->link('specials.php')->retain_query_except(['action']))
      ?>
    </div>
  </div>

<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
    if ( ($action == 'edit') && isset($_GET['sID']) ) {
      $form_action = 'update';

      $product_query = $db->query(sprintf(<<<'EOSQL'
SELECT p.products_id, pd.products_name, p.products_price, s.specials_new_products_price, s.expires_date
 FROM products p INNER JOIN products_description pd ON p.products_id = pd.products_id and pd.language_id = %d INNER JOIN specials s ON pd.products_id = s.products_id
 WHERE s.specials_id = %d
EOSQL
        , (int)$_SESSION['languages_id'], (int)$_GET['sID']));
      $product = $product_query->fetch_assoc();

      $sInfo = new objectInfo($product);
    } else {
      $form_action = 'insert';
      $sInfo = new objectInfo([]);
    }

    $form = new Form('new_special', $Admin->link('specials.php')->retain_query_except(['info', 'sID'])->set_parameter('action', $form_action));
    if ('update' === $form_action) {
      $form->hide('specials_id',  (int)$_GET['sID']);
    }
    echo $form->hide('products_price', $sInfo->products_price ?? '');
?>

    <div class="form-group row" id="zProduct">
      <label for="specialProduct" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_SPECIALS_PRODUCT ?></label>
      <div class="col-sm-9"><?=
        isset($sInfo->products_name)
        ? new Input('n', ['value' => $sInfo->products_name . ' (' . $currencies->format($sInfo->products_price) . ')', 'readonly' => null, 'class' => 'form-control-plaintext'])
        : (new Select('products_id', $discountables ?? Products::list_discountable(), ['id' => 'specialProduct']))->require()
      ?></div>
    </div>

    <div class="form-group row" id="zPrice">
      <label for="specialPrice" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_SPECIALS_SPECIAL_PRICE ?></label>
      <div class="col-sm-9">
        <?= (new Input('specials_price', ['value' => ($sInfo->specials_new_products_price ?? ''), 'id' => 'specialPrice']))->require() ?>
      </div>
    </div>

    <div class="form-group row" id="zDate">
      <label for="specialDate" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_SPECIALS_EXPIRES_DATE ?></label>
      <div class="col-sm-9">
        <?= new Input('expdate', ['value' => (isset($sInfo->expires_date) ? substr($sInfo->expires_date, 0, 4) . '-' . substr($sInfo->expires_date, 5, 2) . '-' . substr($sInfo->expires_date, 8, 2) : ''), 'id' => 'specialDate']) ?>
      </div>
    </div>

    <div class="alert alert-info">
      <?= TEXT_SPECIALS_PRICE_TIP ?>
    </div>

    <?=
    $admin_hooks->cat('formNew'),
    new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success btn-block btn-lg')
    ?>

  </form>

  <script>$('#specialDate').datepicker({ dateFormat: 'yy-mm-dd' });</script>

<?php
  } else {
    $specials_sql = sprintf(<<<'EOSQL'
SELECT p.*, pd.*, s.*
 FROM specials s
  INNER JOIN products p ON p.products_id = s.products_id
  INNER JOIN products_description pd ON p.products_id = pd.products_id AND pd.language_id = %d
 ORDER BY pd.products_name
EOSQL
      , (int)$_SESSION['languages_id']);
    $table_definition = [
      'columns' => [
        [
          'name' => TABLE_HEADING_PRODUCTS,
          'function' => function (&$row) {
            return $row['products_name'];
          },
        ],
        [
          'name' => TABLE_HEADING_PRODUCTS_PRICE,
          'function' => function (&$row) {
            return $GLOBALS['currencies']->format($row['products_price']);
          },
        ],
        [
          'name' => TABLE_HEADING_SPECIAL_PRICE,
          'function' => function (&$row) {
            return $GLOBALS['currencies']->format($row['specials_new_products_price']);
          },
        ],
        [
          'name' => TABLE_HEADING_STATUS,
          'class' => 'text-right',
          'function' => function (&$row) {
            $href = (clone $row['onclick'])->set_parameter('action', 'set_flag');
            return ($row['status'] == '1')
                 ? '<i class="fas fa-check-circle text-success"></i> <a href="' . $href->set_parameter('flag', '0')  . '"><i class="fas fa-times-circle text-muted"></i></a>'
                 : '<a href="' . $href->set_parameter('flag', '1') . '"><i class="fas fa-check-circle text-muted"></i></a> <i class="fas fa-times-circle text-danger"></i>';
          },
        ],
        [
          'name' => TABLE_HEADING_ACTION,
          'class' => 'text-right',
          'function' => function ($row) {
            return (isset($row['info']))
                 ? '<i class="fas fa-chevron-circle-right text-info"></i>'
                 : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
          },
        ],
      ],
      'count_text' => TEXT_DISPLAY_NUMBER_OF_SPECIALS,
      'page' => $_GET['page'] ?? null,
      'web_id' => 'sID',
      'db_id' => 'specials_id',
      'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
      'sql' => $specials_sql,
    ];

    $table_definition['split'] = new Paginator($table_definition);

    $table_definition['split']->display_table();
  }

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
