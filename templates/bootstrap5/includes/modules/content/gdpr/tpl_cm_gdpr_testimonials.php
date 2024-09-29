<div class="<?= MODULE_CONTENT_GDPR_TESTIMONIALS_CONTENT_WIDTH ?> cm-gdpr-testimonials">
  <table class="table">
    <thead class="table-dark">
      <tr>
        <th colspan="2"><?= MODULE_CONTENT_GDPR_TESTIMONIALS_PUBLIC_TITLE ?></th>
      </tr>
      <tr>
        <td colspan="2"><?= MODULE_CONTENT_GDPR_TESTIMONIALS_ANON ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="w-50">
          <p class="text-center"><?= sprintf(MODULE_CONTENT_GDPR_TESTIMONIALS_NUM_TESTIMONIALS, $port_my_data['YOU']['TESTIMONIAL']['COUNT']) ?></p>
        </td>
        <td>
          <ul class="list-group">
            <?php
            foreach ($port_my_data['YOU']['TESTIMONIAL']['LIST'] as $k => $v) {
              echo '<li class="list-group-item">';
                echo '<span class="float-end">';
                  if ($v['ANON'] != 'Y')  {
                    echo '<a title="ANON" role="button" id="anonymize" data-testimonial-id="' . $v['ID'] . '" class="btn btn-sm btn-info btn-update btn-update-testimonial">' . MODULE_CONTENT_GDPR_TESTIMONIALS_ANONYMIZE . '</a>';
                  }
                  echo '&nbsp;<a title="DEL" role="button" id="delete" data-testimonial-id="' . $v['ID'] . '" class="btn btn-sm btn-danger text-white btn-delete btn-update-testimonial">' . MODULE_CONTENT_GDPR_TESTIMONIALS_DELETE . '</a>';
                echo '</span>';
                printf(MODULE_CONTENT_GDPR_TESTIMONIALS_EACH, $v['ID'], $v['DATE']);
                if ($v['ANON'] == 'Y') echo MODULE_CONTENT_GDPR_TESTIMONIALS_ANONYMIZED;
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
