<div class="<?= MODULE_CONTENT_I_SLIDER_CONTENT_WIDTH ?> cm-i-slider">
  <div id="cmislider" class="carousel slide<?= $cm_i_slider_fade ?>" data-bs-ride="carousel" data-bs-interval="<?= $cm_i_slider_interval ?>">
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

      $cm_i_slider_output .= '<div class="carousel-item' . $carousel_active . '">' . PHP_EOL;
        $cm_i_slider_output .= $s_link;
          $cm_i_slider_output .= (new Image('images/' . $a['advert_image'], [], htmlspecialchars($a['advert_title'])))->append_css('d-block w-100');
          if (!Text::is_empty($a['advert_html_text'])) {
            $cm_i_slider_output .= '<div class="carousel-caption">';
              $cm_i_slider_output .= $a['advert_html_text'];
            $cm_i_slider_output .= '</div>';
          }
        $cm_i_slider_output .= $e_link;
      $cm_i_slider_output .= '</div>' . PHP_EOL;

      if ($s == 1) {
        $cm_i_indicator .= '<button type="button" data-bs-target="#cmislider" data-bs-slide-to="0"' . $indicator_active . ' aria-label="Slide 1"></button>';
      } else {
        $cm_i_indicator .= '<button type="button" data-bs-target="#cmislider" data-bs-slide-to="' . ($s-1) . '" class="border border-white bg-secondary rounded" aria-label="Slide ' . $s . '"></button>';
      }
    }
    
    if (MODULE_CONTENT_I_SLIDER_INDICATORS == 'True') {
      $cm_i_indicator_output .= '<div class="carousel-indicators">';
        $cm_i_indicator_output .= $cm_i_indicator;
      $cm_i_indicator_output .= '</div>';
    }
    
    echo $cm_i_indicator_output;
    ?>
    <div class="carousel-inner">
      <?= $cm_i_slider_output ?>
    </div>
    <?php
    if (MODULE_CONTENT_I_SLIDER_CONTROLS == 'True') {
      echo  '<button class="carousel-control-prev" type="button" data-bs-target="#cmislider" data-bs-slide="prev"><span class="border border-white bg-secondary rounded" aria-hidden="true"><span class="carousel-control-prev-icon mt-1"></span></span><span class="visually-hidden">' . MODULE_CONTENT_I_SLIDER_CONTROLS_PREV . '</span></button>';
      echo '<button class="carousel-control-next" type="button" data-bs-target="#cmislider" data-bs-slide="next"><span class="border border-white bg-secondary rounded" aria-hidden="true"><span class="carousel-control-next-icon mt-1"></span></span><span class="visually-hidden">' . MODULE_CONTENT_I_SLIDER_CONTROLS_NEXT . '</span></button>';
    }
    ?>
  </div>
</div>

<?php
$slider_css = <<<EOCSS
<style>@media (max-width: 575.98px) { .cm-i-slider h2 { font-size: 1rem !important; } .cm-i-slider h4 { font-size: 0.8rem !important; } .cm-i-slider .btn { display: none; } }</style>
EOCSS;

$GLOBALS['Template']->add_block($slider_css, 'footer_scripts');
?>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
