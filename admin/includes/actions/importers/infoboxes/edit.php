<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  $iInfo = &$table_definition['info'];
  $heading = TEXT_HEADING_EDIT_IMPORTER;
  $link = $GLOBALS['link']->set_parameter('iID', (int)$iInfo->importers_id);

  $contents = ['form' => new Form('importers', (clone $link)->set_parameter('action', 'save'), 'post', ['enctype' => 'multipart/form-data'])];
  $contents[] = ['text' => TEXT_EDIT_INTRO];
  $contents[] = ['text' => TEXT_IMPORTERS_NAME . '<br>' . new Input('importers_name', ['id' => 'iName', 'value' => $iInfo->importers_name])];
  $contents[] = ['text' => TEXT_IMPORTERS_IMAGE . '<br>' . new Input('importers_image', ['accept' => 'image/*', 'id' => 'inputImportersImage', 'class' => 'form-control'], 'file') . '<label class="form-label" for="inputImportersImage">' . $iInfo->importers_image . '</label>'];
  
  $contents[] = ['text' => sprintf(TEXT_IMPORTERS_ADDRESS, (new Textarea("importers_address", ['cols' => '80', 'rows' => '10', 'id' => "iAddress"]))->set_text($iInfo->importers_address ?? ''))];
  $contents[] = ['text' => sprintf(TEXT_IMPORTERS_EMAIL, new Input('importers_email', ['id' => 'iEmail', 'value' => $iInfo->importers_email ?? '']))];

  $importer_inputs_string = $importer_description_string = '';
  foreach (language::load_all() as $l) {
    $importer = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT *
 FROM importers_info
 WHERE importers_id = %d AND languages_id = %d
EOSQL
      , (int)$iInfo->importers_id, (int)$l['id']))->fetch_assoc();
    $flag_image = $GLOBALS['Admin']->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", [], $l['name']);
    $importer_inputs_string .= '<div class="input-group mb-1"><span class="input-group-text">' . $flag_image . '</span>' . new Input("importers_url[{$l['id']}]", ['id' => "iUrl-{$l['code']}", 'value' => $importer['importers_url']]) . '</div>';
    $importer_description_string .= '<div class="input-group mb-1"><span class="input-group-text">' . $flag_image->set('style', 'vertical-align: top;') . '</span>' . (new Textarea("importers_description[{$l['id']}]", ['cols' => '80', 'rows' => '10', 'id' => "iDescription-{$l['code']}"]))->set_text($importer['importers_description'] ?? '') . '</div>';
  }

  $contents[] = ['text' => TEXT_IMPORTERS_URL . $importer_inputs_string];
  $contents[] = ['text' => TEXT_EDIT_IMPORTERS_DESCRIPTION . $importer_description_string];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
