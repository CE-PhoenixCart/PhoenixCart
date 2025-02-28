<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_EDIT_CATEGORY;

  $contents = [
    'form' => (new Form('categories', $Admin->link('catalog.php', [
      'action' => 'update_category',
      'cPath' => $cPath,
    ]), 'post', ['enctype' => 'multipart/form-data']))->hide('categories_id', $cInfo->categories_id),
  ];
  $contents[] = ['text' => TEXT_EDIT_INTRO];

  $category_inputs_string = $category_description_string = $category_seo_description_string = $category_seo_title_string = '';
  $translations_query = $db->query(sprintf(<<<'EOSQL'
SELECT l.*, cd.*
 FROM languages l LEFT JOIN categories_description cd
   ON l.languages_id = cd.language_id AND cd.categories_id = %d
 ORDER BY l.sort_order
EOSQL
    , $cInfo->categories_id));
  while ($l = $translations_query->fetch_assoc()) {
    $language_icon = $Admin->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", ['alt' => $l['name']]);

    $category_inputs_string .= '<div class="input-group mb-1">';
      $category_inputs_string .= '<span class="input-group-text">'. $language_icon . '</span>';
      $category_inputs_string .= (new Input("categories_name[{$l['languages_id']}]", ['id' => "cName-{$l['code']}"]))->set('value', $l['categories_name'] ?? '')->require();
    $category_inputs_string .= '</div>';

    $category_seo_title_string .= '<div class="input-group mb-1">';
      $category_seo_title_string .= '<span class="input-group-text">'. $language_icon . '</span>';
      $category_seo_title_string .= (new Input("categories_seo_title[{$l['languages_id']}]", ['id' => "cSeoTitle-{$l['code']}"]))->set('value', $l['categories_seo_title'] ?? '');
    $category_seo_title_string .= '</div>';

    $category_description_string .= '<div class="input-group mb-1">';
      $category_description_string .= '<span class="input-group-text">'. $language_icon . '</span>';
      $category_description_string .= (new Textarea("categories_description[{$l['languages_id']}]", ['id' => "cDescription-{$l['code']}", 'cols' => '80', 'rows' => '10']))->set_text($l['categories_description'] ?? '');
    $category_description_string .= '</div>';

    $category_seo_description_string .= '<div class="input-group mb-1">';
      $category_seo_description_string .= '<span class="input-group-text">'. $language_icon . '</span>';
      $category_seo_description_string .= (new Textarea("categories_seo_description[{$l['languages_id']}]", ['id' => "cSeoDescription-{$l['code']}", 'cols' => '80', 'rows' => '10']))->set_text($l['categories_seo_description'] ?? '');
    $category_seo_description_string .= '</div>';
  }

  $contents[] = ['text' => TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string];
  $contents[] = ['text' => TEXT_EDIT_CATEGORIES_SEO_TITLE . $category_seo_title_string];
  $contents[] = ['text' => TEXT_EDIT_CATEGORIES_DESCRIPTION . $category_description_string];
  $contents[] = ['text' => TEXT_EDIT_CATEGORIES_SEO_DESCRIPTION . $category_seo_description_string];
  
  $image = ($cInfo->categories_image) ? $Admin->catalog_image("images/{$cInfo->categories_image}", ['alt' => $cInfo->categories_name]) : '';
  $label = ($cInfo->categories_image) ? '<div class="form-control bg-light text-muted mb-2"><label for="cImg">' .  $cInfo->categories_image . '</label></div>' : '';
  $contents[] = ['text' => TEXT_EDIT_CATEGORIES_IMAGE . $image . $label . new Input('categories_image', ['accept' => 'image/*', 'id' => 'cImg', 'class' => 'form-control'], 'file')];
  
  $contents[] = ['text' => TEXT_EDIT_SORT_ORDER . '<br>' . (new Input('sort_order', ['size' => '2']))->set('value', $cInfo->sort_order)];
  
  $contents[] = [
    'class' => 'd-grid',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success btn-lg mb-1'),
  ];
  
  $contents[] = [
    'class' => 'text-center',
    'text' => $Admin->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light',  $Admin->link('catalog.php', ['cPath' => $cPath, 'cID' => $cInfo->categories_id])),
  ];
