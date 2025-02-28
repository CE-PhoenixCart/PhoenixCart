<div class="<?= MODULE_CONTENT_GDPR_REVIEWS_CONTENT_WIDTH ?> cm-gdpr-reviews">
  <table class="table">
    <thead class="table-dark">
      <tr>
        <th colspan="2"><?= MODULE_CONTENT_GDPR_REVIEWS_PUBLIC_TITLE ?></th>
      </tr>
      <tr>
        <td colspan="2" class="bg-white text-dark"><?= MODULE_CONTENT_GDPR_REVIEWS_ANON ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="w-50">
          <p class="text-center"><?= sprintf(MODULE_CONTENT_GDPR_REVIEWS_NUM_REVIEWS, $port_my_data['YOU']['REVIEW']['COUNT']) ?></p>
        </td>
        <td>
          <ul class="list-group">
            <?php
            $r = 1;
            foreach ($port_my_data['YOU']['REVIEW']['LIST'] as $k => $v) {
              echo '<li class="list-group-item">';
                echo '<span class="float-end">';
                  if ($v['ANON'] != 'Y')  {
                    echo '<a title="ANON" role="button" id="anonymize" data-review-id="' . $v['ID'] . '" class="btn btn-sm btn-info btn-update btn-update-review">' . MODULE_CONTENT_GDPR_REVIEWS_ANONYMIZE . '</a>';
                  }
                  echo '&nbsp;<a title="DEL" role="button" id="delete" data-review-id="' . $v['ID'] . '" class="btn btn-sm btn-danger text-white btn-delete btn-update-review">' . MODULE_CONTENT_GDPR_REVIEWS_DELETE . '</a>';
                echo '</span>';
                printf(MODULE_CONTENT_GDPR_REVIEWS_EACH, $v['PRODUCT'], $v['DATE']);
                echo '<br>' . new star_rating((float)$v['RATING']);
                if ($v['ANON'] == 'Y') echo MODULE_CONTENT_GDPR_REVIEWS_ANONYMIZED;
              echo '</li>';
            }
            ?>
          </ul>
        </td>
      </tr>
    </tbody>
  </table>
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
