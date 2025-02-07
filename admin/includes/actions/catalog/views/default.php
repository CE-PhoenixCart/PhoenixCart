<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License 
*/
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-6 text-start text-lg-end align-self-center pb-1">
      <?=
      $Admin->button(GET_HELP, '', 'btn-dark me-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      $Admin->button('<i class="fas fa-search"></i>', '', 'btn-light me-2', $Admin->link('catalog.php'), ['data-bs-toggle' => 'collapse', 'data-bs-target' => '#collapseSearch', 'aria-expanded' => 'false', 'aria-controls' => 'collapseSearch']),
      isset($_GET['search'])
      ? $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light me-2', $Admin->link('catalog.php'))
      : $Admin->button(IMAGE_NEW_CATEGORY, 'fas fa-sitemap', 'btn-danger me-2', $Admin->link('catalog.php', ['cPath' => $cPath, 'action' => 'new_category']))
        . $Admin->button(IMAGE_NEW_PRODUCT, 'fas fa-boxes', 'btn-danger', $Admin->link('catalog.php', ['cPath' => $cPath, 'action' => 'new_product']))
      ?>
    </div>
  </div>
  
  <div class="collapse row" id="collapseSearch">
    <div class="col-6 align-self-center">
      <?= (new Form('search', $Admin->link('catalog.php'), 'get'))->hide_session_id(),
         '<div class="input-group mb-1">',
          '<span class="input-group-text">', HEADING_TITLE_SEARCH, '</span>',
           new Input('search'),
         '</div>',
       '</form>'
      ?>
    </div>
    <div class="col-6 align-self-center">
      <?= (new Form('goto', $Admin->link('catalog.php'), 'get'))->hide_session_id(),
         '<div class="input-group mb-1">',
           '<span class="input-group-text">', HEADING_TITLE_GOTO, '</span>',
           (new Select(
             'cPath',
             Guarantor::ensure_global('category_tree')->get_selections([['id' => '0', 'text' => TEXT_TOP]], '0'),
             ['class' => 'form-select', 'onchange' => 'this.form.submit();']))->set_selection($current_category_id),
         '</div>',
       '</form>'
       ?>
    </div>
  </div>

  <div class="row g-0">
    <div class="col-12 col-sm-8">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-dark">
            <tr>
              <th><?= TABLE_HEADING_CATEGORIES_PRODUCTS ?></th>
              <th class="text-center"><?= TABLE_HEADING_STATUS ?></th>
              <th class="text-end"><?= TABLE_HEADING_ACTION ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_GET['search'])) {
              $search = Text::prepare($_GET['search']);

              $categories_query = $db->query("SELECT c.*, cd.* FROM categories c, categories_description cd WHERE c.categories_id = cd.categories_id AND cd.language_id = " . (int)$_SESSION['languages_id'] . " AND cd.categories_name LIKE '%" . $db->escape($search) . "%' ORDER BY c.sort_order, cd.categories_name");
            } else {
              $categories_query = $db->query("SELECT c.*, cd.* FROM categories c, categories_description cd WHERE c.parent_id = " . (int)$current_category_id . " AND c.categories_id = cd.categories_id AND cd.language_id = " . (int)$_SESSION['languages_id'] . " ORDER BY c.sort_order, cd.categories_name");
            }
            $categories_count = mysqli_num_rows($categories_query);

            while ($categories = $categories_query->fetch_assoc()) {

              // Get parent_id for subcategories if search
              if (isset($_GET['search'])) {
                $cPath= $categories['parent_id'];
              }

              if (!isset($cInfo) && (!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !Text::is_prefixed_by($action, 'new')) {
                $cInfo = new objectInfo($categories);
              }

              $link = $Admin->link('catalog.php', ['cPath' => Categories::get_path($categories['categories_id'])]);
              if (isset($cInfo->categories_id) && ($categories['categories_id'] == $cInfo->categories_id) ) {
                echo '<tr class="table-active" onclick="document.location.href=\'' . $link . '\'">' . "\n";
                $icon = '<i class="fas fa-chevron-circle-right text-info"></i>';
              } else {
                echo '<tr onclick="document.location.href=\'' . $Admin->link('catalog.php', ['cPath' => $cPath, 'cID' => $categories['categories_id']]) . '\'">' . "\n";
                $icon = '<a href="' . $Admin->link('catalog.php', ['cPath' => $cPath, 'cID' => $categories['categories_id']]) . '"><i class="fas fa-info-circle text-muted"></i></a>';
              }
              ?>
                <th><?= $categories['categories_name'] ?></th>
                <td>&nbsp;</td>
                <td class="text-end">
                  <?=
                  '<a href="' . $link . '"><i class="fas fa-folder-open me-2 text-dark"></i></a>',
                  $icon
                  ?>
                </td>
              </tr>
              <?php
            }

            if (isset($_GET['search'])) {
              $products_query = $db->query("SELECT p.*, pd.*, p2c.categories_id FROM products p, products_description pd, products_to_categories p2c WHERE p.products_id = pd.products_id AND pd.language_id = " . (int)$_SESSION['languages_id'] . " AND p.products_id = p2c.products_id AND ((pd.products_name LIKE '%" . $db->escape($search) . "%') || (p.products_model LIKE '%" . $db->escape($search) . "%') ||  (p.products_gtin LIKE '%" . $db->escape($search) . "%')) ORDER BY pd.products_name");
            } else {
              $products_query = $db->query("SELECT p.*, pd.* FROM products p, products_description pd, products_to_categories p2c WHERE p.products_id = pd.products_id AND pd.language_id = " . (int)$_SESSION['languages_id'] . " AND p.products_id = p2c.products_id AND p2c.categories_id = " . (int)$current_category_id . " ORDER BY pd.products_name");
            }
            $products_count = mysqli_num_rows($products_query);

            while ($products = $products_query->fetch_assoc()) {
// Get categories_id for product if search
              if (isset($_GET['search'])) {
                $cPath = $products['categories_id'];
              }
              $p = new Product($products);

              if ( !isset($product) && !isset($cInfo) && (!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['pID']) && ($_GET['pID'] == $p->get('id')))) && !Text::is_prefixed_by($action, 'new')) {
                $product = $p;
              }
              
              $fragment = ['cPath' => Categories::get_path($cPath), 'products_id' => (int)$p->get('id')];
              $catalog_link = $GLOBALS['Admin']->catalog('product_info.php', $fragment);

              $icons = '<a target="_blank" href="' . $catalog_link . '"><i class="fas fa-eye me-2 text-dark"></i></a>'
                     . '<a href="' . $Admin->link('catalog.php', ['cPath' => $cPath, 'pID' => (int)$p->get('id'), 'action' => 'new_product']) . '"><i class="fas fa-cogs me-2 text-dark"></i></a>';
              if (isset($product) && ($p->get('id') == $product->get('id')) ) {
                echo '<tr class="table-active">';
                $icons .= '<i class="fas fa-chevron-circle-right text-info"></i>';
              } else {
                echo '<tr onclick="document.location.href=\'' . $Admin->link('catalog.php', ['cPath' => $cPath, 'pID' => (int)$p->get('id')]) . '\'">';
                $icons .= '<a href="' . $Admin->link('catalog.php', ['cPath' => $cPath, 'pID' => (int)$p->get('id')]) . '"><i class="fas fa-info-circle text-muted"></i></a>';
              }
              ?>
                <th><?= $p->get('name') ?></th>
                <td class="text-center">
                  <?=
                  ($p->get('status') == '1')
                  ? '<i class="fas fa-check-circle text-success"></i> <a href="'
                    . $Admin->link('catalog.php', ['cPath' => $cPath, 'pID' => (int)$p->get('id'), 'action' => 'set_flag', 'flag' => '0'])
                    . '"><i class="fas fa-times-circle text-muted"></i></a>'
                  : '<a href="' . $Admin->link('catalog.php', ['cPath' => $cPath, 'pID' => (int)$p->get('id'), 'action' => 'set_flag', 'flag' => '1'])
                    . '"><i class="fas fa-check-circle text-muted"></i></a>  <i class="fas fa-times-circle text-danger"></i>'
                  ?>
                </td>
                <td class="text-end"><?= $icons ?></td>
              </tr>
              <?php
            }

            ?>
          </tbody>
        </table>
      </div>

      <div class="row my-1">
        <div class="col"><?= TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count ?></div>
        <div class="col text-end me-2"><?php
        if (isset($cPath_array) && (count($cPath_array) > 0)) {
          $cPath_back = (count($cPath_array) > 1)
                      ? ['cPath' => implode('_', array_slice($cPath_array, 0, -1))]
                      : [];
          echo $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light me-2', $Admin->link('catalog.php', $cPath_back));
        }
        if (!isset($_GET['search'])) {
          echo $Admin->button(IMAGE_NEW_CATEGORY, 'fas fa-sitemap', 'btn-danger me-2', $Admin->link('catalog.php', ['cPath' => $cPath, 'action' => 'new_category']))
             . $Admin->button(IMAGE_NEW_PRODUCT, 'fas fa-boxes', 'btn-danger', $Admin->link('catalog.php', ['cPath' => $cPath, 'action' => 'new_product']));
        }
        ?></div>
      </div>

    </div>

<?php
    if ($action_file = $GLOBALS['Admin']->locate('/infoboxes', $GLOBALS['action'])) {
      require DIR_FS_ADMIN . 'includes/components/infobox.php';
    }
?>

</div>
