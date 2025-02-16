<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_HEADING_NEW_IMPORTER;

  $contents = ['form' => new Form('importers', $GLOBALS['Admin']->link()->set_parameter('action', 'insert'), 'post', ['enctype' => 'multipart/form-data'])];
  $contents[] = ['text' => TEXT_NEW_INTRO];
  $contents[] = ['text' => TEXT_IMPORTERS_NAME . '<br>' . new Input('importers_name', ['id' => 'iName'])];
  $contents[] = ['text' => TEXT_IMPORTERS_IMAGE . '<br>' . new Input('importers_image', ['accept' => 'image/*', 'id' => 'inputImportersImage', 'class' => 'form-control'], 'file') . '<label class="form-label" for="inputImportersImage">' . TEXT_IMPORTERS_IMAGE_LABEL . '</label>'];
  
  $contents[] = ['text' => sprintf(TEXT_IMPORTERS_ADDRESS, new Textarea("importers_address", ['cols' => '80', 'rows' => '10', 'id' => "iAddress"]))];
  $contents[] = ['text' => sprintf(TEXT_IMPORTERS_EMAIL, new Input('importers_email', ['id' => 'iEmail']))];

  $importer_inputs_string = $importer_description_string = '';

  foreach (language::load_all() as $l) {
    $flag_image = $GLOBALS['Admin']->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", [], $l['name']);
    $importer_inputs_string .= '<div class="input-group mb-1"><span class="input-group-text">' . $flag_image . '</span>' . new Input("importers_url[{$l['id']}]", ['id' => "iUrl-{$l['code']}"]) . '</div>';
    $importer_description_string .= '<div class="input-group mb-1"><span class="input-group-text">' . $flag_image->set('style', 'vertical-align: top;') . '</span>' . new Textarea("importers_description[{$l['id']}]", ['cols' => '80', 'rows' => '10', 'id' => "iDescription-{$l['code']}"]) . '</div>';
  }

  $contents[] = ['text' => TEXT_IMPORTERS_URL . $importer_inputs_string];
  $contents[] = ['text' => TEXT_IMPORTERS_DESCRIPTION . $importer_description_string];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
