<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  \Outgoing::sendEmail();
  
  $GLOBALS['messageStack']->add_session(READY_EMAILS_SENT, 'success');
  Href::redirect($Admin->link('outgoing.php'));
