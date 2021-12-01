<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_admin_siteWide_hMenu {

  public $version = '1.0.0';

  public static function sort_box_links($a, $b) {
    return strcasecmp($a['title'], $b['title']);
  }

  public function listen_injectBodyStart() {
    if (basename(Request::get_page()) !== 'login.php') {
      $cl_box_groups = [];

      if ($dir = @dir(DIR_FS_ADMIN . 'includes/boxes')) {
        $files = [];

        while ($file = $dir->read()) {
          if (!is_dir("{$dir->path}/$file") && (pathinfo($file, PATHINFO_EXTENSION) === 'php')) {
            $files[] = $file;
          }
        }

        $dir->close();

        natcasesort($files);

        foreach ( $files as $file ) {
          $path = DIR_FS_ADMIN . "includes/languages/{$_SESSION['language']}/modules/boxes/$file";
          if ( file_exists($path) ) {
            include_once $path;
          }

          include_once "{$dir->path}/$file";
        }
      }

      usort($cl_box_groups, function ($a, $b) {
        return strcasecmp(strip_tags($a['heading']), strip_tags($b['heading']));
      });

      foreach ( $cl_box_groups as &$group ) {
        usort($group['apps'], 'static::sort_box_links');
      }

      $n = 1;
      $mr = '';
      foreach ($cl_box_groups as $groups) {
        $mr .= '<li class="nav-item dropdown">';
          $mr .= '<a class="nav-link dropdown-toggle" href="#" id="navbar_' . $n . '" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $groups['heading'] . '</a>';
          $al = ($n > 6) ? ' dropdown-menu-right' : '';
          $mr .= '<div class="dropdown-menu' . $al . '" aria-labelledby="navbar_' . $n . '">';
          foreach ($groups['apps'] as $app) {
            $mr .= '<a class="dropdown-item" href="' . $app['link'] . '">' . $app['title'] . '</a>';
          }
          $mr .= '</div>';
        $mr .= '</li>' . PHP_EOL;

        $n++;
      }

      $icon = $GLOBALS['Admin']->image('images/CE-Phoenix-30-30.png', [], 'CE Phoenix v' . Versions::get('Phoenix'), 30, 30);
      $output = '<nav class="navbar navbar-expand-md sticky-top navbar-dark bg-dark">';
        $output .= '<a class="navbar-brand" href="' . $GLOBALS['Admin']->link('index.php') . '">' . $icon->set_responsive(false) . '</a>';
        $output .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>';
        $output .= '<div class="collapse navbar-collapse" id="navbarAdmin">';
          $output .= '<ul class="navbar-nav mr-auto">' . $mr . '</ul>';
        $output .= '</div>';
      $output .= '</nav>';

      $output .= '<div class="col bg-light mb-1 border-bottom d-print-none">';
        $output .= '<ul class="nav justify-content-end">';
          $output .= '<li class="nav-item"><a class="nav-link" target="_blank" rel="noreferrer" href="https://phoenixcart.org/forum/">' . HEADER_TITLE_PHOENIX_CLUB . '</a></li>';
          $output .= '<li class="nav-item"><a class="nav-link" target="_blank" rel="noreferrer" href="https://phoenixcart.org/phoenixcartwiki/index.php">' . HEADER_TITLE_PHOENIX_WIKI . '</a></li>';
          $output .= '<li class="nav-item"><a class="nav-link" target="_blank" rel="noreferrer" href="https://phoenixcart.org/forum/addons/">' . HEADER_TITLE_CERTIFIED_ADDONS . '</a></li>';
          $output .= '<li class="nav-item"><a class="nav-link" href="' . $GLOBALS['Admin']->link('certified_addons.php') . '">' . HEADER_TITLE_CERTIFIED_DEVELOPERS . '</a></li>';
          $output .= '<li class="nav-item"><a class="nav-link" href="' . $GLOBALS['Admin']->catalog('') . '">' . HEADER_TITLE_ONLINE_CATALOG . '</a></li>';
          $output .= '<li class="nav-item"><a class="nav-link text-danger" href="' . $GLOBALS['Admin']->link('login.php', ['action' => 'logoff']) . '">'
                   . sprintf(HEADER_TITLE_LOGOFF, $_SESSION['admin']['username'])
                   . '</a></li>';
        $output .= '</ul>';
      $output .= '</div>';

      return $output;
    }
  }

}
