<div class="<?= MODULE_CONTENT_HEADER_SEARCH_CONTENT_WIDTH ?> cm-header-search">
  <?= (new Form('quick_find', $GLOBALS['Linker']->build('advanced_search_result.php', [], false), 'get'))->hide_session_id() ?>
    <div class="input-group">
      <?= (new Input('keywords', ['autocomplete' => 'off', 'aria-label' => TEXT_SEARCH_PLACEHOLDER, 'placeholder' => TEXT_SEARCH_PLACEHOLDER], 'search'))->require() ?>
      <button type="submit" class="btn btn-info"><i class="fas fa-search"></i></button>
    </div>
  </form>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
