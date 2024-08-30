<div class="<?= MODULE_CONTENT_I_SLIDER_CONTENT_WIDTH ?> cm-i-slider">
  <div id="cmislider" class="carousel slide<?= $cm_i_slider_fade ?>" data-ride="carousel" data-interval="<?= $cm_i_slider_interval ?>">
    <?php
    foreach ($cm_i_slider_adverts as $s => $a) {
      $carousel_active = $indicator_active = $s_link = $e_link = null;

      if ($s == 1) {
        $carousel_active = ' active';
        $indicator_active = ' class="border border-white bg-secondary rounded active" aria-current="true"';
      }

      if (!Text::is_empty($a['advert_url'])) {
        if (filter_var($a['advert_url'], FILTER_VALIDATE_URL)) {
          $s_link = '<a target="_blank" href="' . $a['advert_url'] . '">';
          $e_link = '</a>';
        } else {
          $fragment = url_query::parse($a['advert_fragment'] ?? '');

          $s_link = '<a href="' . $GLOBALS['Linker']->build($a['advert_url'], $fragment) . '">';
          $e_link = '</a>';
        }
      }

      $cm_i_slider_output .= '<div class="carousel-item' . $carousel_active . '">';
        $cm_i_slider_output .= $s_link;
          $cm_i_slider_output .= (new Image('images/' . $a['advert_image'], [], htmlspecialchars($a['advert_title'])))->append_css('d-block w-100');
          if (!Text::is_empty($a['advert_html_text'])) {
            $cm_i_slider_output .= '<div class="carousel-caption d-none d-md-block">';
              $cm_i_slider_output .= $a['advert_html_text'];
            $cm_i_slider_output .= '</div>';
          }
        $cm_i_slider_output .= $e_link;
      $cm_i_slider_output .= '</div>';

      if ($s == 1) {
        $cm_i_indicator .= '<li data-target="#cmislider" data-slide-to="0"' . $indicator_active . ' aria-label="Slide 1"></li>';
      } else {
        $cm_i_indicator .= '<li class="border border-white bg-secondary rounded" data-target="#cmislider" data-slide-to="' . ($s-1) . '" aria-label="Slide ' . $s . '"></li>';
      }
    }
    
    if (MODULE_CONTENT_I_SLIDER_INDICATORS == 'True') {
      $cm_i_indicator_output .= '<ol class="carousel-indicators">';
        $cm_i_indicator_output .= $cm_i_indicator;
      $cm_i_indicator_output .= '</ol>';
    }
    
    echo $cm_i_indicator_output;
    ?>
    <div class="carousel-inner">
      <?= $cm_i_slider_output ?>
    </div>
    <?php
    if (MODULE_CONTENT_I_SLIDER_CONTROLS == 'True') {
      echo  '<a class="carousel-control-prev" href="#cmislider" role="button" data-slide="prev"><span class="border border-white bg-secondary rounded" aria-hidden="true"><span class="carousel-control-prev-icon mt-1"></span></span><span class="sr-only">' . MODULE_CONTENT_I_SLIDER_CONTROLS_PREV . '</span></a>';
      echo '<a class="carousel-control-next" href="#cmislider" role="button" data-slide="next"><span class="border border-white bg-secondary rounded" aria-hidden="true"><span class="carousel-control-next-icon mt-1"></span></span><span class="sr-only">' . MODULE_CONTENT_I_SLIDER_CONTROLS_NEXT . '</span></a>';
    }
    ?>
  </div>
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
