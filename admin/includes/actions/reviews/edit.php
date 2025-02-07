<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $rID = Text::input($_GET['rID']);

  $reviews_query = $db->query(sprintf(<<<'EOSQL'
SELECT r.*, rd.*, p.products_image, pd.products_name
 FROM reviews r
   INNER JOIN reviews_description rd ON r.reviews_id = rd.reviews_id
   LEFT JOIN products p ON p.products_id = r.products_id
   LEFT JOIN products_description pd ON r.products_id = pd.products_id AND pd.language_id = %d
 WHERE r.reviews_id = %d
 ORDER BY rd.languages_id = %d DESC
 LIMIT 1
EOSQL
    , (int)$_SESSION['languages_id'], (int)$rID, (int)$_SESSION['languages_id']));
  $rInfo = $reviews_query->fetch_object();

  if (!isset($rInfo->reviews_status)) {
    $rInfo->reviews_status = '1';
  }
  $link->set_parameter('rID', (int)$_GET['rID']);
  $form = new Form('review', (clone $link)->set_parameter('action', 'preview'));
  $form->hide('reviews_id', $rInfo->reviews_id)
       ->hide('reviews_status', $rInfo->reviews_status)
       ->hide('products_id', $rInfo->products_id)
       ->hide('products_image', $rInfo->products_image)
       ->hide('date_added', $rInfo->date_added);

  $hook_action = 'formEdit';
  $button = new Button(IMAGE_PREVIEW, 'fas fa-eye', 'btn-info me-2')
          . $Admin->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link);
  $action = 'save';
