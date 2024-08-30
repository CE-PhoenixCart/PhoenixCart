<li class="nav-item nb-search">
  <a class="nav-link" href="#" data-toggle="modal" data-target="#searchModal">
    <?= MODULE_NAVBAR_SEARCH_PUBLIC_TEXT ?>
  </a>
</li>

<?php
$form = new Form('quick_find', $GLOBALS['Linker']->build('advanced_search_result.php')->set_include_session(false), 'get');
$form->hide_session_id()->hide('search_in_description', '0');
$input = new Input('keywords', ['autocomplete' => 'off'], 'search');
$input->require();
      
$search_text = TEXT_SEARCH_PLACEHOLDER;

$searchModal = <<<SM
<div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-5">
        {$form}
          <div class="input-group input-group-lg">
            <div class="input-group-prepend">
              <span class="input-group-text">{$search_text}</span>
            </div>
            {$input}
            <div class="input-group-append">
              <button type="submit" class="btn btn-secondary btn-search"><i class="fas fa-magnifying-glass fa-fw"></i></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
SM;

$GLOBALS['Template']->add_block($searchModal, 'footer_scripts');
?>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>