<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $listing_split = new splitPageResults($listing_sql, $num_list, 'p.products_id');

  if ($GLOBALS['messageStack']->size('product_action') > 0) {
    echo $GLOBALS['messageStack']->output('product_action');
  }
?>

<div class="contentText">

<?php
  if ($listing_split->number_of_rows > 0) {
    if ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) {
?>
  <div class="row align-items-center">
    <div class="col-sm-6 d-none d-sm-block">
      <?= $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS) ?>
    </div>
    <div class="col-sm-6">
      <?= $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS) ?>
    </div>
  </div>
<?php
    }
?>
    <div class="card mb-2 card-body alert-filters">
      <ul class="nav">
        <li class="nav-item dropdown">
          <a href="#" class="nav-link text-dark dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= TEXT_SORT_BY ?><span class="caret"></span></a>

          <div class="dropdown-menu">
            <?php
    foreach ($column_list as $i => $column) {
      if ($column_specifications[$column]['sortable']) {
        echo splitPageResults::create_sort_heading($_GET['sort'], $i+1, $column_specifications[$column]['heading']);
      }
    }
            ?>
          </div>

        </li>
      </ul>
    </div>

  <?php
    $listing_query = $GLOBALS['db']->query($listing_split->sql_query);

    $prod_list_contents = '';

    while ($listing = $listing_query->fetch_assoc()) {
      $listing['link'] = Product::build_link($listing['products_id'], null);
      $product = new Product($listing);
      $card = [
        'show_buttons' => true,
      ];

      if (!Text::is_empty($product->get('seo_description'))) {
        $card['extra'] = '<div class="pt-2 font-weight-lighter">'
                       . $product->get('seo_description')
                       . '</div>' . PHP_EOL;
      }

      $prod_list_contents .= '<div class="col mb-2">';
        $prod_list_contents .= '<div class="card h-100 is-product"' . $product->get('data_attributes') . '>' . PHP_EOL;

          ob_start();
          include $GLOBALS['Template']->map('product_card.php', 'component');
          $prod_list_contents .= ob_get_clean();

        $prod_list_contents .= '</div>' . PHP_EOL;
      $prod_list_contents .= '</div>' . PHP_EOL;
    }

    // filter hook
    echo $GLOBALS['hooks']->cat('drawForm');

    echo '<div class="' . IS_PRODUCT_PRODUCTS_DISPLAY_ROW . '">' . PHP_EOL;
      echo $prod_list_contents;
    echo '</div>' . PHP_EOL;

    if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
  <div class="row align-items-center">
    <div class="col-sm-6 d-none d-sm-block">
      <?= $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS) ?>
    </div>
    <div class="col-sm-6">
      <?= $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS) ?>
    </div>
  </div>
<?php
    }
  } else {
    echo '<div class="alert alert-info" role="alert">' . TEXT_NO_PRODUCTS . '</div>';
  }
?>

</div>
