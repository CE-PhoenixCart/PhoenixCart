<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';
  
  $get_addons_link = '';
  $get_addons_link .= '<div class="btn-group" role="group">';
    $get_addons_link .= '<button type="button" class="btn btn-dark me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
      $get_addons_link .= GET_ADDONS;
    $get_addons_link .= '</button>';
    $get_addons_link .= '<div class="dropdown-menu">';
    foreach (GET_ADDONS_LINKS as $k => $v) {
      $get_addons_link .= '<a class="dropdown-item" target="_blank" href="' . $v . '">' . $k . '</a>';
    }
    $get_addons_link .= '</div>';
  $get_addons_link .= '</div>';
  
  $languages = language::load_all();
  foreach ($languages as $i => $l) {
    $languages[$i]['icon'] = (string)$Admin->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", ['alt' => $l['name']]);
  }

  $option_page = $_GET['option_page'] ?? 1;
  $value_page = $_GET['value_page'] ?? 1;
  $attribute_page = $_GET['attribute_page'] ?? 1;

  $link = $Admin->link('products_attributes.php', [
    'option_page' => (int)$option_page,
    'value_page' => (int)$value_page,
    'attribute_page' => (int)$attribute_page,
  ]);

  require 'includes/segments/process_action.php';

  $get_link = (clone $link)->set_parameter('formid', $_SESSION['sessiontoken']);

  $product_selector = new Select('products_id', Products::list_options(), ['class' => 'form-select']);
  $options = $db->fetch_all(sprintf(<<<'EOSQL'
SELECT products_options_id AS id, products_options_name AS text, products_options.*
 FROM products_options
 WHERE language_id = %d
 ORDER BY products_options_name
EOSQL
    , (int)$_SESSION['languages_id']));
  $values = $db->fetch_all(sprintf(<<<'EOSQL'
SELECT pov.*, pov.products_options_values_id AS id, pov.products_options_values_name AS text, pov2po.products_options_id 
 FROM products_options_values pov LEFT JOIN products_options_values_to_products_options pov2po ON pov.products_options_values_id = pov2po.products_options_values_id 
 WHERE pov.language_id = %d 
 ORDER BY pov.products_options_values_name 
EOSQL
    , (int)$_SESSION['languages_id']));    
  $grouped_values = [];
  foreach ($values as $value) {
    $grouped_values[] = ['id' => $value['id'], 'text' => $value['text'], 'parameters' => ['data-id' => $value['products_options_id']]];
  }
  
  $default_selection = [['id' => '', 'text' => PLEASE_SELECT_OPTION]];

  require 'includes/template_top.php';
  ?>
  
  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE_ATRIB ?></h1>
    </div>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?=
      $get_addons_link,
      $Admin->button(GET_HELP, '', 'btn-dark me-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons')
      ?>
    </div>
  </div>

  <?php
  $attributes_sql = sprintf(<<<'EOSQL'
SELECT pa.*, pd.products_name, po.products_options_name, pov.products_options_values_name
 FROM products_attributes pa
   LEFT JOIN products_options po ON po.products_options_id = pa.options_id AND po.language_id = %1$d
   LEFT JOIN products_options_values pov ON pov.products_options_values_id = pa.options_values_id AND pov.language_id = %1$d
   LEFT JOIN products_description pd ON pa.products_id = pd.products_id AND pd.language_id = %1$d
 ORDER BY pd.products_name, po.sort_order, pov.sort_order
EOSQL
    , (int)$_SESSION['languages_id']);

  $attributes_split = new splitPageResults($attribute_page, MAX_ROW_LISTS_OPTIONS, $attributes_sql, $attributes_query_numrows);
  ?>

  <div class="accordion" id="accordionAttributes">
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAttrib" aria-expanded="false" aria-controls="collapseAttrib">
          <?= HEADING_TITLE_ATRIB ?>
        </button>
      </h2>
      <div id="collapseAttrib" class="accordion-collapse collapse" data-bs-parent="#accordionAttributes">
        <div class="accordion-body">
          <div class="table-responsive-sm">
            <?= new Form('attributes', (clone $link)->set_parameter('action', ('update_attribute' === $action) ? 'update_product_attribute' : 'add_product_attributes')) ?>
              <table class="table table-striped">
                <thead class="table-dark">
                  <tr>
                    <th><?= TABLE_HEADING_PRODUCT ?></th>
                    <th><?= TABLE_HEADING_OPT_NAME ?></th>
                    <th><?= TABLE_HEADING_OPT_VALUE ?></th>
                    <th class="text-end" style="width: 120px;"><?= TABLE_HEADING_OPT_PRICE ?></th>
                    <th class="text-center" style="width: 120px;"><?= TABLE_HEADING_OPT_PRICE_PREFIX ?></th>
                    <th class="text-end" style="width: 120px;"><?= TABLE_HEADING_ACTION ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $next_id = 1;
                  $attributes_query = $db->query($attributes_sql);
                  while ($attributes_values = $attributes_query->fetch_assoc()) {
                    if (($action == 'update_attribute') && ($_GET['attribute_id'] == $attributes_values['products_attributes_id'])) {
                      ?>
                      <tr class="table-success">
                        <td>
                          <input type="hidden" name="attribute_id" value="<?= $attributes_values['products_attributes_id'] ?>">
                          <?= $product_selector->set_selection($attributes_values['products_id'])->require()->set_options(array_merge($default_selection, $product_selector->get_options())) ?>
                        </td>
                        <td>
                          <?= (new Select('options_id', array_merge($default_selection, $options), ['class' => 'form-select']))->set_selection($attributes_values['options_id'])->require() ?>
                        </td>
                        <td>
                          <?= (new Select('values_id', array_merge($default_selection, $grouped_values), ['class' => 'form-select']))->set_selection($attributes_values['options_values_id'])->require() ?>
                        </td>
                        <td class="text-end"><?= new Input('value_price', ['value' => $attributes_values['options_values_price']]) ?></td>
                        <td class="text-end"><?= new Input('price_prefix', ['size' => 2, 'value' => $attributes_values['price_prefix']]) ?></td>
                        <td class="text-end"><?=
                          new Button('', 'fas fa-save text-success', 'btn-link'),
                          $Admin->button('', 'fas fa-times text-dark', 'btn-link', $link)
                        ?></td>
                      </tr>
                      <?php
                      if (DOWNLOAD_ENABLED == 'true') {
                        $download_query = $db->query(sprintf(<<<'EOSQL'
SELECT products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount
 FROM products_attributes_download
 WHERE products_attributes_id = %d
EOSQL
                          , (int)$attributes_values['products_attributes_id']));
                        $download = $download_query->fetch_assoc();
                        ?>
                        <tr>
                          <td colspan="6">
                            <table>
                              <tr>
                                <td><?= TABLE_HEADING_DOWNLOAD ?></td>
                                <td><?= TABLE_TEXT_FILENAME ?></td>
                                <td><?= (new Input('products_attributes_filename'))->set('value', $download['products_attributes_filename'] ?? '') ?></td>
                                <td><?= TABLE_TEXT_MAX_DAYS ?></td>
                                <td><?= (new Input('products_attributes_maxdays'))->set('value', $download['products_attributes_maxdays'] ?? '') ?></td>
                                <td><?= TABLE_TEXT_MAX_COUNT ?></td>
                                <td><?= (new Input('products_attributes_maxcount'))->set('value', $download['products_attributes_maxcount'] ?? '') ?></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      <?php
                      }
                    } elseif (($action == 'delete_product_attribute') && ($_GET['attribute_id'] == $attributes_values['products_attributes_id'])) {
                      ?>
                      <tr class="table-danger">
                        <td><?= $attributes_values['products_name'] ?? '' ?></td>
                        <td><?= $attributes_values['products_options_name'] ?? '' ?></td>
                        <td><?= $attributes_values['products_options_values_name'] ?? '' ?></td>
                        <td class="text-end"><?= $attributes_values["options_values_price"] ?></td>
                        <td class="text-center"><?= $attributes_values["price_prefix"] ?></td>
                        <td class="text-end"><?=
                          $Admin->button('', 'fas fa-trash text-danger', 'btn-link', (clone $get_link)->set_parameter('action', 'delete_attribute')->set_parameter('attribute_id', (int)$_GET['attribute_id'])),
                          $Admin->button('', 'fas fa-times text-dark', 'btn-link', $link)
                        ?></td>
                      </tr>
                      <?php
                    } else {
                      $attribute_link = (clone $get_link)->set_parameter('attribute_id', $attributes_values['products_attributes_id']);
                    ?>
                    <tr>
                      <td><?= $attributes_values['products_name'] ?? '' ?></td>
                      <td><?= $attributes_values['products_options_name'] ?? '' ?></td>
                      <td><?= $attributes_values['products_options_values_name'] ?? '' ?></td>
                      <td class="text-end"><?= $attributes_values["options_values_price"] ?></td>
                      <td class="text-center"><?= $attributes_values["price_prefix"] ?></td>
                      <td class="text-end"><?=
                        $Admin->button('', 'fas fa-cogs text-dark', 'btn-link', (clone $attribute_link)->set_parameter('action', 'update_attribute')),
                        $Admin->button('', 'fas fa-trash text-danger', 'btn-link', $attribute_link->set_parameter('action', 'delete_product_attribute'))
                      ?></td>
                    </tr>
                    <?php
                    }
                  }

                  if ($action != 'update_attribute') {
                    ?>
                    <tr class="table-success">
                      <td>
                        <?= $product_selector->set_selection()->require()->set_options(array_merge($default_selection, $product_selector->get_options())) ?>
                      </td>
                      <td>
                        <?= (new Select('options_id', array_merge($default_selection, $options), ['class' => 'form-select']))->require() ?>
                      </td>
                      <td>
                        <?= (new Select('values_id', array_merge($default_selection, $grouped_values), ['class' => 'form-select']))->require() ?>
                      </td>
                      <td class="text-end"><?= new Input('value_price', ['value' => '0']) ?></td>
                      <td class="text-end"><?= new Input('price_prefix', ['value' => '+']) ?></td>
                      <td class="text-end"><?= new Button('', 'fas fa-plus text-success', 'btn-link') ?></td>
                    </tr>
                    <?php
                    if (DOWNLOAD_ENABLED == 'true') {
                      ?>
                      <tr class="table-info">
                        <td colspan="6">
                          <h6><?= TABLE_HEADING_DOWNLOAD ?></h6>
                          <div class="row">
                            <div class="col"><?= TABLE_TEXT_FILENAME ?><br><?= new Input('products_attributes_filename') ?></div>
                            <div class="col-3"><?= TABLE_TEXT_MAX_DAYS ?><br><?= (new Input('products_attributes_maxdays'))->set('value', DOWNLOAD_MAX_DAYS) ?></div>
                            <div class="col-3"><?= TABLE_TEXT_MAX_COUNT ?><br><?= (new Input('products_attributes_maxcount'))->set('value', DOWNLOAD_MAX_COUNT) ?></div>
                          </div>
                        </td>
                      </tr>
                      <?php
                    }
                  }
                  ?>
                </tbody>
              </table>
            </form>
          </div>
          
          <p class="my-2 text-end me-2"><?= $attributes_split->display_links($attributes_query_numrows, MAX_ROW_LISTS_OPTIONS, MAX_DISPLAY_PAGE_LINKS, $attribute_page, ['option_page' => $option_page, 'value_page' => $value_page], 'attribute_page') ?></p>

        </div>
      </div>
    </div>
    
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOpt" aria-expanded="false" aria-controls="collapseOpt">
          <?= HEADING_TITLE_OPT ?>
        </button>
      </h2>
      <div id="collapseOpt" class="accordion-collapse collapse" data-bs-parent="#accordionAttributes">
        <div class="accordion-body">
          <?php
          if ($action == 'delete_product_option') {
            ?>

            <h2 class="lead"><?= array_column($options, 'text', 'id')[(int)$_GET['option_id']] ?></h2>

            <div class="table-responsive-sm">
              <table class="table table-striped">
                <?php
                $products = $db->query(sprintf(<<<'EOSQL'
SELECT p.products_id, pd.products_name, pov.products_options_values_name
 FROM products_description pd
   INNER JOIN products p ON pd.products_id = p.products_id
   INNER JOIN products_attributes pa ON pa.products_id = p.products_id
   INNER JOIN products_options_values pov
     ON pov.products_options_values_id = pa.options_values_id
    AND pd.language_id = pov.language_id
 WHERE pov.language_id = %d AND pa.options_id = %d
 ORDER BY pd.products_name
EOSQL
                  , (int)$_SESSION['languages_id'], (int)$_GET['option_id']));

                if (mysqli_num_rows($products)) {
                  ?>
                  <thead class="table-dark">
                    <tr>
                      <th><?= TABLE_HEADING_ID ?></th>
                      <th><?= TABLE_HEADING_PRODUCT ?></th>
                      <th><?= TABLE_HEADING_OPT_VALUE ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    while ($products_values = $products->fetch_assoc()) {
                      ?>
                      <tr>
                        <td><?= $products_values['products_id'] ?></td>
                        <td><?= $products_values['products_name'] ?></td>
                        <td><?= $products_values['products_options_values_name'] ?></td>
                      </tr>
                      <?php
                    }
                    ?>
                    <tr>
                      <td class="table-danger" colspan="2"><?= TEXT_WARNING_OF_DELETE ?></td>
                      <td class="table-danger text-end" colspan="1"><?= $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-danger btn-sm', $link) ?></td>
                    </tr>
                  </tbody>
                  <?php
                } else {
                  ?>
                  <tbody>
                    <tr>
                      <td class="table-success" colspan="2"><?= TEXT_OK_TO_DELETE ?></td>
                      <td class="table-success text-end" colspan="1"><?=
                        $Admin->button('', 'fas fa-trash text-danger', 'btn-link btn-sm me-2', (clone $get_link)->set_parameter('action', 'delete_option')->set_parameter('option_id', (int)$_GET['option_id'])),
                        $Admin->button('', 'fas fa-times text-dark', 'btn-light btn-sm', $link)
                      ?></td>
                    </tr>
                  </tbody>
                  <?php
                }
                  ?>
              </table>
            </div>
            <?php
          } else {
            ?>
            <div class="table-responsive-sm">
              <table class="table table-striped">
                <thead class="table-dark">
                  <tr>
                    <th><?= TABLE_HEADING_OPT_NAME ?></th>
                    <th><?= TABLE_HEADING_OPT_SORT_ORDER ?></th>
                    <th class="text-end" style="width: 120px;"><?= TABLE_HEADING_ACTION ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $next_id = 1;
                  $options_query = $db->query("SELECT * FROM products_options WHERE language_id = " . (int)$_SESSION['languages_id'] . " ORDER BY sort_order");
                  while ($options_values = $options_query->fetch_assoc()) {
                    if (($action == 'update_option') && ($_GET['option_id'] == $options_values['products_options_id'])) {
                      $inputs = $sort = '';
                      foreach ($languages as $l) {
                        $option_name = $db->query("SELECT products_options_name, sort_order FROM products_options WHERE products_options_id = " . (int)$options_values['products_options_id'] . " AND language_id = " . (int)$l['id']);
                        $option_name = $option_name->fetch_assoc();

                        $inputs .= '<div class="input-group mb-1">';
                          $inputs .= '<span class="input-group-text">'. $l['icon'] . '</span>';
                          $inputs .= (new Input("option_name[{$l['id']}]", ['id' => "oName-{$l['code']}"]))->require()->set('value', $option_name['products_options_name']);
                        $inputs .= '</div>';

                        $sort .= '<div class="input-group mb-1">';
                          $sort .= '<span class="input-group-text">'. $l['icon'] . '</span>';
                          $sort .= (new Input("sort_order[{$l['id']}]", ['id' => "oSort-{$l['code']}"]))->require()->set('value', $option_name['sort_order']);
                        $sort .= '</div>';

                      }
                      ?>
                    <tr class="table-success">
                      <td colspan="3">
                        <?= new Form('option', (clone $link)->set_parameter('action', 'update_option_name')) ?>
                          <div class="row">
                            <div class="col-6">
                              <input type="hidden" name="option_id" value="<?= $options_values['products_options_id'] ?>">
                              <?= $inputs ?>
                            </div>
                            <div class="col-2">
                              <?= $sort ?>
                            </div>
                            <div class="col-4 text-end">
                              <?= new Button('', 'fas fa-save text-success', 'btn-link me-2'), $Admin->button('', 'fas fa-times text-dark', 'btn-link', $link) ?>
                            </div>
                          </div>
                        </form>
                      </td>
                    </tr>
                    <?php
                  } else {
                    ?>
                    <tr>
                      <td><?= $options_values['products_options_name'] ?></td>
                      <td class="w-25"><?= $options_values['sort_order'] ?></td>
                      <td class="w-25 text-end"><?=
                        $Admin->button('', 'fas fa-cogs text-dark', 'btn-link', (clone $get_link)->set_parameter('action', 'update_option')->set_parameter('option_id', (int)$options_values['products_options_id'])),
                        $Admin->button('', 'fas fa-trash text-danger', 'btn-link', (clone $get_link)->set_parameter('action', 'delete_product_option')->set_parameter('option_id', (int)$options_values['products_options_id']))
                      ?></td>
                    </tr>
                    <?php
                  }
                }

                if ($action != 'update_option') {
                  $max_options_id_query = $db->query("SELECT COALESCE(MAX(products_options_id), 0) + 1 AS next_id FROM products_options");
                  $max_options_id_values = $max_options_id_query->fetch_assoc();
                  $next_id = $max_options_id_values['next_id'];

                  $inputs = $sort = '';
                  foreach ($languages as $l) {
                    $inputs .= '<div class="input-group mb-1">';
                      $inputs .= '<span class="input-group-text">'. $l['icon'] . '</span>';
                      $inputs .= (new Input("option_name[{$l['id']}]",['id' => "oName-{$l['code']}"]))->require();
                    $inputs .= '</div>';

                    $sort .= '<div class="input-group mb-1">';
                      $sort .= '<span class="input-group-text">'. $l['icon'] . '</span>';
                      $sort .= (new Input("sort_order[{$l['id']}]", ['id' => "oSort-{$l['code']}"]))->require();
                    $sort .= '</div>';

                  }
                  ?>
                  <tr class="table-success">
                    <td colspan="3">
                      <?= new Form('options', (clone $link)->set_parameter('action', 'add_product_options')) ?>
                        <input type="hidden" name="products_options_id" value="<?= $next_id ?>">
                        <div class="row">
                          <div class="col-6">
                            <?= $inputs ?>
                          </div>
                          <div class="col-2">
                            <?= $sort ?>
                          </div>
                          <div class="col-4 text-end">
                            <?= new Button('', 'fas fa-plus text-success', 'btn-link') ?>
                          </div>
                        </div>
                      </form>
                    </td>
                  </tr>
                  <?php
                }
                ?>
                </tbody>
              </table>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
    </div>
    
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVal" aria-expanded="false" aria-controls="collapseVal">
          <?= HEADING_TITLE_VAL ?>
        </button>
      </h2>
      <div id="collapseVal" class="accordion-collapse collapse" data-bs-parent="#accordionAttributes">
        <div class="accordion-body">
          <?php
          if ($action == 'delete_option_value') {
            $values_values = $db->query("SELECT products_options_values_id, products_options_values_name FROM products_options_values WHERE products_options_values_id = " . (int)$_GET['value_id'] . " AND language_id = " . (int)$_SESSION['languages_id'])->fetch_assoc();
            ?>

            <div class="table-responsive-sm">
              <table class="table table-striped">
                <?php
                $products = $db->query("SELECT p.products_id, pd.products_name, po.products_options_name FROM products p, products_attributes pa, products_options po, products_description pd WHERE pd.products_id = p.products_id AND pd.language_id = " . (int)$_SESSION['languages_id'] . " AND po.language_id = " . (int)$_SESSION['languages_id'] . " AND pa.products_id = p.products_id AND pa.options_values_id = " . (int)$_GET['value_id'] . " AND po.products_options_id = pa.options_id ORDER BY pd.products_name");

                if (mysqli_num_rows($products)) {
                  ?>
                  <thead class="table-dark">
                    <tr>
                      <th><?= TABLE_HEADING_ID ?></th>
                      <th><?= TABLE_HEADING_PRODUCT ?></th>
                      <th><?= TABLE_HEADING_OPT_NAME ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    while ($products_values = $products->fetch_assoc()) {
                      ?>
                      <tr>
                        <td><?= $products_values['products_id'] ?></td>
                        <td><?= $products_values['products_name'] ?></td>
                        <td><?= $products_values['products_options_name'] ?></td>
                      </tr>
                      <?php
                    }
                    ?>
                    <tr>
                      <td class="table-danger" colspan="2"><?= TEXT_WARNING_OF_DELETE ?></td>
                      <td class="table-danger text-end" colspan="1"><?= $Admin->button(IMAGE_CANCEL, 'fas fa-angle-left', 'btn-danger btn-sm', $link) ?></td>
                    </tr>
                  </tbody>
                  <?php
                } else {
                  ?>
                  <tr>
                    <td class="table-success" colspan="3"><?= TEXT_OK_TO_DELETE ?></td>
                  </tr>
                  <tr>
                    <td class="text-end" colspan="3"><?=
                      $Admin->button('', 'fas fa-trash text-danger', 'btn-link me-2', (clone $get_link)->set_parameter('action', 'delete_value')->set_parameter('value_id', (int)$_GET['value_id'])),
                      $Admin->button('', 'fas fa-times text-dark', 'btn-link', $link)
                    ?></td>
                  </tr>
                  <?php
                }
                ?>
              </table>
            </div>
            <?php
          } else {
            $values_query = $db->query(sprintf(<<<'EOSQL'
SELECT po.*, pov.*, pov2po.*
 FROM products_options_values pov
   INNER JOIN products_options_values_to_products_options pov2po ON pov.products_options_values_id = pov2po.products_options_values_id
   INNER JOIN products_options po ON po.products_options_id = pov2po.products_options_id
 WHERE pov.language_id = %1$d AND po.language_id = %1$d
 ORDER BY po.products_options_name, pov.sort_order
EOSQL
              , (int)$_SESSION['languages_id']));
            ?>

            <div class="table-responsive-sm">
              <table class="table table-striped">
                <thead class="table-dark">
                  <tr>
                    <th class="w-25"><?= TABLE_HEADING_OPT_NAME ?></th>
                    <th class="w-25"><?= TABLE_HEADING_OPT_VALUE ?></th>
                    <th><?= TABLE_HEADING_OPT_SORT_ORDER ?></th>
                    <th class="text-end"><?= TABLE_HEADING_ACTION ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  while ($values_values = $values_query->fetch_assoc()) {
                    if (($action == 'update_option_value') && ($_GET['value_id'] == $values_values['products_options_values_id'])) {
                      $inputs = $sort = '';
                      foreach ($languages as $l) {
                        $value_name = $db->query("SELECT products_options_values_name, sort_order FROM products_options_values WHERE products_options_values_id = " . (int)$values_values['products_options_values_id'] . " AND language_id = " . (int)$l['id'])->fetch_assoc();

                        $inputs .= '<div class="input-group mb-1">';
                          $inputs .= '<span class="input-group-text">'. $l['icon'] . '</span>';
                          $inputs .= (new Input("value_name[{$l['id']}]", ['id' => "vName-{$l['code']}"]))->require()->set('value', $value_name['products_options_values_name']);
                        $inputs .= '</div>';

                        $sort .= '<div class="input-group mb-1">';
                          $sort .= '<span class="input-group-text">'. $l['icon'] . '</span>';
                          $sort .= (new Input("sort_order[{$l['id']}]", ['id' => "vSort-{$l['code']}"]))->require()->set('value', $value_name['sort_order']);
                        $sort .= '</div>';
                      }
                      ?>
                      <tr class="table-success">
                        <td colspan="4">
                          <?= new Form('values', (clone $link)->set_parameter('action', 'update_value')) ?>
                            <input type="hidden" name="value_id" value="<?= $values_values['products_options_values_id'] ?>">
                            <div class="row">
                              <div class="col-3">
                                <?= (new Select('option_id', $options, ['class' => 'form-select']))->set_selection($values_values['products_options_id']) ?>
                              </div>
                              <div class="col-3">
                                <?= $inputs ?>
                              </div>
                              <div class="col-2">
                                <?= $sort ?>
                              </div>
                              <div class="col-4 text-end">
                                <?= new Button('', 'fas fa-save text-success', 'btn-link'), $Admin->button('', 'fas fa-times text-dark', 'btn-link', $link) ?>
                              </div>
                            </div>
                          </form>
                        </td>
                      </tr>
                      <?php
                    } else {
                      $value_link = (clone $get_link)->set_parameter('value_id', $values_values['products_options_values_id']);
                      ?>
                      <tr>
                        <td><?= $values_values['products_options_name'] ?></td>
                        <td><?= $values_values['products_options_values_name'] ?></td>
                        <td><?= $values_values['sort_order'] ?></td>
                        <td class="text-end"><?=
                          $Admin->button('', 'fas fa-cogs text-dark', 'btn-link', (clone $value_link)->set_parameter('action', 'update_option_value')),
                          $Admin->button('', 'fas fa-trash text-danger', 'btn-link', $value_link->set_parameter('action', 'delete_option_value'))
                        ?></td>
                      </tr>
                      <?php
                    }
                  }
                  if ($action != 'update_option_value') {
                    $max_values_id_query = $db->query("SELECT COALESCE(MAX(products_options_values_id), 0) + 1 AS next_id FROM products_options_values");
                    $max_values_id_values = $max_values_id_query->fetch_assoc();
                    $next_id = $max_values_id_values['next_id'];

                    $inputs = $sort = '';
                    foreach ($languages as $l) {
                      $inputs .= '<div class="input-group mb-1">';
                        $inputs .= '<span class="input-group-text">'. $l['icon'] . '</span>';
                        $inputs .= (new Input("value_name[{$l['id']}]", ['id' => "vName-{$l['code']}"]))->require();
                      $inputs .= '</div>';

                      $sort .= '<div class="input-group mb-1">';
                        $sort .= '<span class="input-group-text">'. $l['icon'] . '</span>';
                        $sort .= (new Input("sort_order[{$l['id']}]", ['id' => "vSort-{$l['code']}"]))->require();
                      $sort .= '</div>';
                    }
                    ?>
                    <tr class="table-success">
                      <td colspan="4">
                        <?= new Form('values', $link->set_parameter('action', 'add_product_option_values')) ?>
                          <div class="row">
                            <div class="col-3">
                              <?= (new Select('option_id', $options, ['class' => 'form-select'])) ?>
                            </div>
                            <div class="col-3">
                              <input type="hidden" name="value_id" value="<?= $next_id ?>">
                              <?= $inputs ?>
                            </div>
                            <div class="col-2">
                              <?= $sort ?>
                            </div>
                            <div class="col-4 text-end">
                              <?= new Button('', 'fas fa-plus text-success', 'btn-link') ?>
                            </div>
                          </div>
                        </form>
                      </td>
                    </tr>
                    <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  
<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
