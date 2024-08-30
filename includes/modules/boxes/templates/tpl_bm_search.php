<div class="card mb-2 bm-search">
  <div class="card-header">
    <?= MODULE_BOXES_SEARCH_BOX_TITLE ?>
  </div>
  <div class="card-body text-center">
    <?= (new Form('quick_find', $GLOBALS['Linker']->build('advanced_search_result.php')->set_include_session(false), 'get'))->hide_session_id()->hide('search_in_description', '0') ?>
      <div class="input-group">
        <?= (new Input('keywords', ['autocomplete' => 'off', 'placeholder' => TEXT_SEARCH_PLACEHOLDER], 'search'))->require() ?>
        <div class="input-group-append">
          <button type="submit" class="btn btn-info btn-search"><i class="fas fa-search"></i></button>
        </div>
      </div>
    </form>
  </div>
  <div class="card-footer">
    <?= MODULE_BOXES_SEARCH_BOX_TEXT . '<br><a href="' . $GLOBALS['Linker']->build('advanced_search.php') . '"><strong>' . MODULE_BOXES_SEARCH_BOX_ADVANCED_SEARCH . '</strong></a>' ?>
  </div>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
