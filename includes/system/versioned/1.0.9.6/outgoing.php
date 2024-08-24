<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class Outgoing {

    public static function parse() {
      $modules = glob('includes/modules/outgoing/*.php');

      foreach ($modules as $m) {
        include_once($m);

        call_user_func(['Outgoing_' . basename($m, '.php'), 'execute']);
      }
    }

    public static function delete() {
      $modules = glob('includes/modules/outgoing/*.php');

      foreach ($modules as $m) {
        include_once($m);

        call_user_func(['Outgoing_' . basename($m, '.php'), 'remove']);
      }
    }
    
    public static function show_pages() {
      $display_pages = [];
      
      $modules = glob(DIR_FS_CATALOG . 'includes/modules/outgoing/*.php');

      foreach ($modules as $m) {
        include_once($m);
        
        $display_pages = call_user_func(['Outgoing_' . basename($m, '.php'), 'pages']);
      }
      
      $display_pages[] = 'checkout_success.php';
     
      return array_unique($display_pages);
    }
    
    public static function merge_tags() {
      $merge_tags = [];
      
      $modules = glob(DIR_FS_CATALOG . 'includes/modules/outgoing/*.php');

      foreach ($modules as $m) {
        include_once($m);
        
        $mt = call_user_func(['Outgoing_' . basename($m, '.php'), 'merge_tags']);
      }
      
      return $mt;
    }

    public static function email_dropdown() {
      $slug_array[] = ['id'   => '', 'text' => SLUG_SELECT];
      
      $modules = glob(DIR_FS_CATALOG . 'includes/modules/outgoing/*.php');

      foreach ($modules as $m) {
        include_once($m);
        
        $slug_array[] = call_user_func(['Outgoing_' . basename($m, '.php'), 'email']);
      }
     
      return array_filter($slug_array);
    }

    public static function all_dropdown() {
      $slug_array[] = ['id'   => '', 'text' => SLUG_SELECT];
      
      $modules = glob(DIR_FS_CATALOG . 'includes/modules/outgoing/*.php');

      foreach ($modules as $m) {
        include_once($m);
        
        $slug_array[] = call_user_func(['Outgoing_' . basename($m, '.php'), 'dropdown']);
      }
     
      return array_filter($slug_array);
    }

    public static function sendEmail() {
      $merge_tags = self::merge_tags();
      
      $outgoing = []; $slugs = [];

      foreach ($merge_tags as $mq => $tq) {
        foreach($tq as $bq => $cq) {
          $replacement_array[] = $bq;
        }
      }
      $replacement_array = array_unique($replacement_array);

      $slugworth  = ['{{FNAME}}', '{{LNAME}}', '{{EMAIL}}'];
      foreach ($replacement_array as $y) {
        $slugworth[] = $y;
      }

      $outgoing_tpl_query = $GLOBALS['db']->query("select * from outgoing_tpl");
      while ($outgoing_tpl = $outgoing_tpl_query->fetch_assoc()) {
        $slugs[$outgoing_tpl['slug']] = $outgoing_tpl;
      }

      $outgoing_emails_query = $GLOBALS['db']->query("select * from outgoing where send_at < now()");
      if (mysqli_num_rows($outgoing_emails_query) > 0) {
        while ($outgoing_emails = $outgoing_emails_query->fetch_assoc()) {
          $outgoing[$outgoing_emails['id']]['email'] = $slugs[$outgoing_emails['slug']];
          $outgoing[$outgoing_emails['id']]['TO']['FNAME'] = $outgoing_emails['fname'];
          $outgoing[$outgoing_emails['id']]['TO']['LNAME'] = $outgoing_emails['lname'];
          $outgoing[$outgoing_emails['id']]['TO']['EMAIL'] = $outgoing_emails['email_address'];

          $outgoing[$outgoing_emails['id']]['REPLACEMENTS']['{{FNAME}}'] = $outgoing_emails['fname'];
          $outgoing[$outgoing_emails['id']]['REPLACEMENTS']['{{LNAME}}'] = $outgoing_emails['lname'];
          $outgoing[$outgoing_emails['id']]['REPLACEMENTS']['{{EMAIL}}'] = $outgoing_emails['email_address'];

          foreach ($replacement_array as $m) {
            $outgoing[$outgoing_emails['id']]['REPLACEMENTS'][$m] = null;
          }

          $_mt = json_decode($outgoing_emails['merge_tags'], JSON_FORCE_OBJECT);

          foreach ($_mt as $_m => $_t) {
            $outgoing[$outgoing_emails['id']]['REPLACEMENTS']['{{' . strtoupper($_m) . '}}'] = $_t;
          }

          $wonka = $outgoing[$outgoing_emails['id']]['REPLACEMENTS'];

          $outgoing[$outgoing_emails['id']]['email']['title'] = str_replace($slugworth, $wonka, $outgoing[$outgoing_emails['id']]['email']['title']);
          $outgoing[$outgoing_emails['id']]['email']['text']  = str_replace($slugworth, $wonka, $outgoing[$outgoing_emails['id']]['email']['text']);
        }
      }
    
      if (sizeof($outgoing) > 0) {
        foreach ($outgoing as $o => $g) {
          if (!Text::is_empty($g['email']['text'])) {
            $mimemessage = new email();
            $mimemessage->add_message($g['email']['text']);
            $mimemessage->build_message();
            $mimemessage->send($g['TO']['FNAME'] . ' ' . $g['TO']['LNAME'], $g['TO']['EMAIL'], STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $g['email']['title']);
          }
          
          $GLOBALS['db']->query("delete from outgoing where id = '" . (int)$o . "'");
        }
      }
    }
    
    public static function getEmail($arr) {
      $merge_tags = self::merge_tags();
      
      $outgoing = []; $slugs = [];

      foreach ($merge_tags as $mq => $tq) {
        foreach($tq as $bq => $cq) {
          $replacement_array[] = $bq;
        }
      }
      $replacement_array = array_unique($replacement_array);

      $slugworth  =['{{FNAME}}', '{{LNAME}}', '{{EMAIL}}'];
      foreach ($replacement_array as $y) {
        $slugworth[] = $y;
      }
      
      $outgoing_tpl_query = $GLOBALS['db']->query("SELECT * FROM outgoing_tpl WHERE slug = '" . $arr['slug'] . "'");
      while ($outgoing_tpl = $outgoing_tpl_query->fetch_assoc()) {
        $slugs[$outgoing_tpl['slug']] = $outgoing_tpl;
      }

      $outgoing_emails_query = $GLOBALS['db']->query("select * from outgoing where id = " . $arr['id']);
      if (mysqli_num_rows($outgoing_emails_query) > 0) {
        while ($outgoing_emails = $outgoing_emails_query->fetch_assoc()) {
          $outgoing[$outgoing_emails['id']]['email'] = $slugs[$outgoing_emails['slug']];
          $outgoing[$outgoing_emails['id']]['TO']['FNAME'] = $outgoing_emails['fname'];
          $outgoing[$outgoing_emails['id']]['TO']['LNAME'] = $outgoing_emails['lname'];
          $outgoing[$outgoing_emails['id']]['TO']['EMAIL'] = $outgoing_emails['email_address'];
          $outgoing[$outgoing_emails['id']]['TO']['SEND_AT'] = $outgoing_emails['send_at'];

          $outgoing[$outgoing_emails['id']]['REPLACEMENTS']['{{FNAME}}'] = $outgoing_emails['fname'];
          $outgoing[$outgoing_emails['id']]['REPLACEMENTS']['{{LNAME}}'] = $outgoing_emails['lname'];
          $outgoing[$outgoing_emails['id']]['REPLACEMENTS']['{{EMAIL}}'] = $outgoing_emails['email_address'];

          foreach ($replacement_array as $m) {
            $outgoing[$outgoing_emails['id']]['REPLACEMENTS'][$m] = null;
          }

          $_mt = json_decode($outgoing_emails['merge_tags'], JSON_FORCE_OBJECT);

          foreach ($_mt as $_m => $_t) {
            $outgoing[$outgoing_emails['id']]['REPLACEMENTS']['{{' . strtoupper($_m) . '}}'] = $_t;
          }

          $wonka = $outgoing[$outgoing_emails['id']]['REPLACEMENTS'];

          $outgoing[$outgoing_emails['id']]['email']['title'] = str_replace($slugworth, $wonka, $outgoing[$outgoing_emails['id']]['email']['title']);
          $outgoing[$outgoing_emails['id']]['email']['text']  = str_replace($slugworth, $wonka, $outgoing[$outgoing_emails['id']]['email']['text']);
        }
      }
      
      return json_encode($outgoing, JSON_PRETTY_PRINT);
    }
    
    public static function deBug() {
      $merge_tags    = self::merge_tags();
      
      $outgoing = []; $slugs = [];

      foreach ($merge_tags as $mq => $tq) {
        foreach($tq as $bq => $cq) {
          $replacement_array[] = $bq;
        }
      }
      $replacement_array = array_unique($replacement_array);

      $slugworth  =['{{FNAME}}', '{{LNAME}}', '{{EMAIL}}'];
      foreach ($replacement_array as $y) {
        $slugworth[] = $y;
      }
      
      $outgoing_tpl_query = $GLOBALS['db']->query("select * from outgoing_tpl");
      while ($outgoing_tpl = $outgoing_tpl_query->fetch_assoc()) {
        $slugs[$outgoing_tpl['slug']] = $outgoing_tpl;
      }

      $outgoing_emails_query = $GLOBALS['db']->query("select * from outgoing order by send_at");
      if (mysqli_num_rows($outgoing_emails_query) > 0) {
        while ($outgoing_emails = $outgoing_emails_query->fetch_assoc()) {
          $outgoing[$outgoing_emails['id']]['email'] = $slugs[$outgoing_emails['slug']];
          $outgoing[$outgoing_emails['id']]['TO']['FNAME'] = $outgoing_emails['fname'];
          $outgoing[$outgoing_emails['id']]['TO']['LNAME'] = $outgoing_emails['lname'];
          $outgoing[$outgoing_emails['id']]['TO']['EMAIL'] = $outgoing_emails['email_address'];
          $outgoing[$outgoing_emails['id']]['TO']['SEND_AT'] = $outgoing_emails['send_at'];

          $outgoing[$outgoing_emails['id']]['REPLACEMENTS']['{{FNAME}}'] = $outgoing_emails['fname'];
          $outgoing[$outgoing_emails['id']]['REPLACEMENTS']['{{LNAME}}'] = $outgoing_emails['lname'];
          $outgoing[$outgoing_emails['id']]['REPLACEMENTS']['{{EMAIL}}'] = $outgoing_emails['email_address'];

          foreach ($replacement_array as $m) {
            $outgoing[$outgoing_emails['id']]['REPLACEMENTS'][$m] = null;
          }

          $_mt = json_decode($outgoing_emails['merge_tags'], JSON_FORCE_OBJECT);

          foreach ($_mt as $_m => $_t) {
            $outgoing[$outgoing_emails['id']]['REPLACEMENTS']['{{' . strtoupper($_m) . '}}'] = $_t;
          }

          $wonka = $outgoing[$outgoing_emails['id']]['REPLACEMENTS'];

          $outgoing[$outgoing_emails['id']]['email']['title'] = str_replace($slugworth, $wonka, $outgoing[$outgoing_emails['id']]['email']['title']);
          $outgoing[$outgoing_emails['id']]['email']['text']  = str_replace($slugworth, $wonka, $outgoing[$outgoing_emails['id']]['email']['text']);
        }
      }
      
      return json_encode($outgoing, JSON_PRETTY_PRINT);
    }
  
  }
