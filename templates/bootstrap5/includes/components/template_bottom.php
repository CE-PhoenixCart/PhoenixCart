<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License 
*/
?>

        </main>
      
      <?= $hooks->cat('injectBodyContentEnd') ?>

      </div> <!-- bodyContent //-->

<?php
  if ( $Template->has_blocks('boxes_column_left') && ($tpl_template->getGridColumnWidth() > 0) ) {
?>

      <div id="columnLeft" class="col-md-<?= $tpl_template->getGridColumnWidth() ?> order-2 order-md-1">
        <?= $Template->get_blocks('boxes_column_left') ?>
      </div>

<?php
  }

  if ( $Template->has_blocks('boxes_column_right') && ($tpl_template->getGridColumnWidth() > 0) ) {
?>

      <div id="columnRight" class="col-md-<?= $tpl_template->getGridColumnWidth() ?> order-last">
        <?= $Template->get_blocks('boxes_column_right') ?>
      </div>

<?php
  }
?>

    </div> <!-- row -->

    <?= $hooks->cat('injectBodyWrapperEnd') ?>

  </div> <!-- bodyWrapper //-->

  <?php
  echo $hooks->cat('injectBeforeFooter');

  require $Template->map('footer.php', 'component');

  echo $hooks->cat('injectAfterFooter');

  echo $hooks->cat('injectSiteEnd');

  echo $Template->get_blocks('footer_scripts');

  echo $hooks->cat('injectBodyEnd');
  ?>

</body>
</html>
