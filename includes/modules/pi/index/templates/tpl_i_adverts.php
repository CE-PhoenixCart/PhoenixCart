<div class="<?= I_ADVERTS_CONTENT_WIDTH ?> i-adverts">
  <ul class="list-group list-group-horizontal-md">
    <?php
    $i_advert_output = '';
    foreach ($i_adverts_linkage as $i => $l) {
      $start = $end = '';
      $i_advert_output .= '<li class="list-group-item border-0 p-0">' . PHP_EOL;
      
        if (!Text::is_empty($l['advert_url'])) {
          if (filter_var($l['advert_url'], FILTER_VALIDATE_URL)) {
            $start = '<a target="_blank" href="' . $l['advert_url'] . '">';
            $end = '</a>';
          } else {
            $fragment = url_query::parse($l['advert_fragment'] ?? '');

            $start = '<a href="' . $GLOBALS['Linker']->build($l['advert_url'], $fragment) . '">';
            $end = '</a>';
          }
        }
        
        $i_advert_output .= $start;
        $i_advert_output .= '<div class="card border-0">';
          $i_advert_output .= new Image('images/' . $l['advert_image'], ['alt' => '', 'class' => 'card-img rounded-0']) . PHP_EOL;
          if (!Text::is_empty($l['advert_html_text'])) {
            $i_advert_output .= '<div class="card-img-overlay text-white">';
              $i_advert_output .= $l['advert_html_text'];
            $i_advert_output .= '</div>';
          }
        $i_advert_output .= '</div>';
        $i_advert_output .= $end;
        
      $i_advert_output .= '</li>' . PHP_EOL;
    }

    echo $i_advert_output;
    ?>
  </ul>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>



