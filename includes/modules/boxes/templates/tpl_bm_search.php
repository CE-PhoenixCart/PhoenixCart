<div class="card mb-2 bm-search">
  <div class="card-header">
    <?= MODULE_BOXES_SEARCH_BOX_TITLE ?>
  </div>
  <div class="card-body text-center">
    <?= $form ?>
      <div class="input-group">
        <?= $input ?>
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
