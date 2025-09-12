<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_shop_product_info_pulseCart {

  public function listen_injectBodyEnd() {
    global $product, $currency;

    $product_id = (int)$product->get('id');
    $product_name = json_encode($product->get('name'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    $sku = json_encode($product->get('model'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    $price = (float)$product->get('final_price');
    $currency_js = json_encode($currency, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

    $pulse = <<<HTML
<script>
  window.pulse = window.pulse || [];
  window.pulse.push({
    event: 'product_view',
    payload: {
      product_name: $product_name,
      sku: $sku,
      price: $price,
      currency: $currency_js,
      product_id: $product_id
    },
    page_url: window.location.href,
    referrer: document.referrer,
    domain: window.location.hostname,
    timestamp: new Date().toISOString()
  });
</script>
HTML;

    return PHP_EOL . $pulse;
  }

}
