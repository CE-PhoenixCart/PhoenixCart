<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $mInfo = &$table_definition['info'];
  $heading = TEXT_HEADING_EDIT_MANUFACTURER . ' <small>' . TEXT_EDIT_INTRO . '</small>';
  $link = $GLOBALS['link']->set_parameter('mID', (int)$mInfo->manufacturers_id);

  $contents = ['form' => new Form('manufacturers', (clone $link)->set_parameter('action', 'save'), 'post', ['enctype' => 'multipart/form-data'])];
  $contents[] = ['text' => TEXT_EDIT_INTRO];
  $contents[] = ['text' => TEXT_MANUFACTURERS_NAME . '<br>' . new Input('manufacturers_name', ['value' => $mInfo->manufacturers_name])];
  $contents[] = ['text' => TEXT_MANUFACTURERS_IMAGE . '<br><div class="custom-file mb-2">' . new Input('manufacturers_image', ['id' => 'inputManufacturersImage', 'class' => 'custom-file-input'], 'file') . '<label class="custom-file-label" for="inputManufacturersImage">' . $mInfo->manufacturers_image . '</label></div>'];

  $manufacturer_inputs_string = $manufacturer_description_string = $manufacturer_seo_description_string = $manufacturer_seo_title_string = '';
  foreach (language::load_all() as $l) {
    $manufacturer = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT manufacturers_url, manufacturers_seo_title, manufacturers_description, manufacturers_seo_description
 FROM manufacturers_info
 WHERE manufacturers_id = %d AND languages_id = %d
EOSQL
      , (int)$mInfo->manufacturers_id, (int)$l['id']))->fetch_assoc();
    $flag_image = $GLOBALS['Admin']->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", [], $l['name']);
    $manufacturer_inputs_string .= '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text">' . $flag_image . '</span></div>' . new Input("manufacturers_url[{$l['id']}]", ['value' => $manufacturer['manufacturers_url']]) . '</div>';
    $manufacturer_seo_title_string .= '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text">' . $flag_image . '</span></div>' . new Input("manufacturers_seo_title[{$l['id']}]", ['value' => $manufacturer['manufacturers_seo_title']]) . '</div>';
    $manufacturer_description_string .= '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text">' . $flag_image->set('style', 'vertical-align: top;') . '</span></div>' . (new Textarea("manufacturers_description[{$l['id']}]", ['cols' => '80', 'rows' => '10']))->set_text($manufacturer['manufacturers_description']) . '</div>';
    $manufacturer_seo_description_string .= '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text">' . $flag_image . '</span></div>' . (new Textarea("manufacturers_seo_description[{$l['id']}]", ['cols' => '80', 'rows' => '10']))->set_text($manufacturer['manufacturers_seo_description']) . '</div>';
  }

  $contents[] = ['text' => TEXT_MANUFACTURERS_URL . $manufacturer_inputs_string];
  $contents[] = ['text' => TEXT_EDIT_MANUFACTURERS_SEO_TITLE . $manufacturer_seo_title_string];
  $contents[] = ['text' => TEXT_EDIT_MANUFACTURERS_DESCRIPTION . $manufacturer_description_string];
  $contents[] = ['text' => TEXT_EDIT_MANUFACTURERS_SEO_DESCRIPTION . $manufacturer_seo_description_string];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success mr-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
