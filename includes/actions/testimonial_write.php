<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  namespace Phoenix\Actions;

  class testimonial_write {

    public static function execute() {
      self::load_lang();

      $nickname = \Text::prepare($_POST['nickname']);
      $text = \Text::prepare($_POST['text']);

      $GLOBALS['db']->query("INSERT INTO testimonials (customers_id, customers_name, date_added, testimonials_status) VALUES (" . (int)$_SESSION['customer_id'] . ", '" . $GLOBALS['db']->escape($nickname) . "', NOW(), '0')");

      $testimonials_id = mysqli_insert_id($GLOBALS['db']);

      $GLOBALS['db']->query("INSERT INTO testimonials_description (testimonials_id, languages_id, testimonials_text) VALUES (" . (int)$testimonials_id . ", " . (int)$_SESSION['languages_id'] . ", '" . $GLOBALS['db']->escape($text) . "')");

      $GLOBALS['messageStack']->add_session('testimonial', sprintf(MODULE_CONTENT_TESTIMONIALS_WRITE_THANK_YOU, $nickname), 'success');

      \Href::redirect(\Guarantor::ensure_global('Linker')->build('testimonials.php'));
    }

    static function load_lang() {
      require_once \language::map_to_translation('modules/content/testimonials/cm_t_write.php');
    }

  }
