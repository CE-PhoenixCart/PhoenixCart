<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_NEW_CATEGORY;

  $contents = ['form' => new Form('newcategory', $Admin->link('catalog.php', ['action' => 'insert_category', 'cPath' => $cPath]), 'post', ['enctype' => 'multipart/form-data'])];
  $contents[] = ['text' => TEXT_NEW_CATEGORY_INTRO];

  $category_inputs_string = $category_description_string = $category_seo_description_string = $category_seo_title_string = '';
  foreach (language::load_all() as $l) {
    $language_icon = $Admin->catalog_image("includes/languages/{$l['directory']}/images/{$l['image']}", [], $l['name']);

    $category_inputs_string .= '<div class="input-group mb-1">';
      $category_inputs_string .= '<div class="input-group-prepend">';
        $category_inputs_string .= '<span class="input-group-text">' . $language_icon . '</span>';
      $category_inputs_string .= '</div>';
      $category_inputs_string .= (new Input("categories_name[{$l['id']}]"))->require();
    $category_inputs_string .= '</div>';

    $category_seo_title_string .= '<div class="input-group mb-1">';
      $category_seo_title_string .= '<div class="input-group-prepend">';
        $category_seo_title_string .= '<span class="input-group-text">'. $language_icon . '</span>';
      $category_seo_title_string .= '</div>';
      $category_seo_title_string .= new Input("categories_seo_title[{$l['id']}]");
    $category_seo_title_string .= '</div>';

    $category_description_string .= '<div class="input-group mb-1">';
      $category_description_string .= '<div class="input-group-prepend">';
        $category_description_string .= '<span class="input-group-text">'. $language_icon . '</span>';
      $category_description_string .= '</div>';
      $category_description_string .= new Textarea("categories_description[{$l['id']}]", ['cols' => '80', 'rows' => '10']);
    $category_description_string .= '</div>';

    $category_seo_description_string .= '<div class="input-group mb-1">';
      $category_seo_description_string .= '<div class="input-group-prepend">';
        $category_seo_description_string .= '<span class="input-group-text">'. $language_icon . '</span>';
      $category_seo_description_string .= '</div>';
      $category_seo_description_string .= new Textarea("categories_seo_description[{$l['id']}]", ['cols' => '80', 'rows' => '10']);
    $category_seo_description_string .= '</div>';
  }

  $contents[] = ['text' => TEXT_CATEGORIES_NAME . $category_inputs_string];
  $contents[] = ['text' => TEXT_CATEGORIES_SEO_TITLE . $category_seo_title_string];
  $contents[] = ['text' => TEXT_CATEGORIES_DESCRIPTION . $category_description_string];
  $contents[] = ['text' => TEXT_CATEGORIES_SEO_DESCRIPTION . $category_seo_description_string];
  $contents[] = [
    'text' => TEXT_EDIT_CATEGORIES_IMAGE
            . '<div class="custom-file mb-2">'
            . new Input('categories_image', ['id' => 'cImg', 'class' => 'custom-file-input'], 'file')
            . '<label class="custom-file-label" for="cImg">&nbsp;</label></div>'
  ];
  $contents[] = ['text' => TEXT_SORT_ORDER . '<br>' . new Input('sort_order', ['size' => '2'])];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success btn-block btn-lg mb-1')
            . $Admin->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $Admin->link('catalog.php', ['cPath' => $cPath])),
  ];
