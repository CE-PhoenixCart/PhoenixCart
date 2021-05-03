<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Notifications {

    public static function mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {
      if (SEND_EMAILS !== 'true') {
        return false;
      }

// Instantiate a new mail object
      $message = new email();
      $message->add_message($email_text);
      $message->build_message();

      return $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
    }

    public static function notify($trigger, $subject) {
      $notified = false;

      if (defined('MODULE_NOTIFICATIONS_INSTALLED') && !Text::is_empty(MODULE_NOTIFICATIONS_INSTALLED)) {
        foreach ((array)explode(';', MODULE_NOTIFICATIONS_INSTALLED) as $basename) {
          $class = pathinfo($basename, PATHINFO_FILENAME);

          if (!Guarantor::ensure_global($class)->isEnabled()) {
            continue;
          }

          if (in_array($trigger, $class::TRIGGERS)) {
            $result = $GLOBALS[$class]->notify($subject);
            if (!is_null($result)) {
              $notified = $notified || $result;
            }
          }
        }
      }

      return $notified;
    }

  }
