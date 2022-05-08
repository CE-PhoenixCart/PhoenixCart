<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_HEADING_NEW_MANUFACTURER;

  $contents = ['form' => new Form('manufacturers', $GLOBALS['Admin']->link()->set_parameter('action', 'insert'), 'post', ['enctype' => 'multipart/form-data'])];
  $contents[] = ['text' => TEXT_NEW_INTRO];
  $contents[] = ['text' => TEXT_MANUFACTURERS_NAME . '<br>' . new Input('manufacturers_name')];
  $contents[] = ['text' => TEXT_MANUFACTURERS_IMAGE . '<br><div class="custom-file mb-2">' . new Input('manufacturers_image', ['id' => 'inputManufacturersImage', 'class' => 'custom-file-input'], 'file') . '<label class="custom-file-label" for="inputManufacturersImage">' . TEXT_MANUFACTURERS_IMAGE_LABEL . '</label></div>'];

  $manufacturer_inputs_string = $manufacturer_description_string = $manufacturer_seo_description_string = $manufacturer_seo_title_string = '';

  foreach (language::load_all() as $l) {
    $flag_image = $GLOBALS['Admin']->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", [], $l['name']);
    $manufacturer_inputs_string .= '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text">' . $flag_image . '</span></div>' . new Input("manufacturers_url[{$l['id']}]") . '</div>';
    $manufacturer_seo_title_string .= '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text">' . $flag_image . '</span></div>' . new Input("manufacturers_seo_title[{$l['id']}]") . '</div>';
    $manufacturer_description_string .= '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text">' . $flag_image->set('style', 'vertical-align: top;') . '</span></div>' . new Textarea("manufacturers_description[{$l['id']}]", ['cols' => '80', 'rows' => '10']) . '</div>';
    $manufacturer_seo_description_string .= '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text">' . $flag_image . '</span></div>' . new Textarea("manufacturers_seo_description[{$l['id']}]", ['cols' => '80', 'rows' => '10']) . '</div>';
  }

  $contents[] = ['text' => TEXT_MANUFACTURERS_URL . $manufacturer_inputs_string];
  $contents[] = ['text' => TEXT_MANUFACTURERS_SEO_TITLE . $manufacturer_seo_title_string];
  $contents[] = ['text' => TEXT_MANUFACTURERS_DESCRIPTION . $manufacturer_description_string];
  $contents[] = ['text' => TEXT_MANUFACTURERS_SEO_DESCRIPTION . $manufacturer_seo_description_string];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success mr-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
