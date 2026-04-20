<?php
if (!defined('ABSPATH')) exit;

function cg_live_crypto_cards_render_wrapper() {
    $settings = cg_live_crypto_cards_get_settings();
    $layout   = esc_attr($settings['layout']);
    $dark     = esc_attr($settings['dark_mode']);

    ob_start();
    ?>
    <div id="cg-live-cards-wrapper"
         class="cg-layout-<?php echo $layout; ?>"
         data-dark-mode="<?php echo $dark; ?>">
        <div id="cg-live-cards"></div>
    </div>
    <?php
    return ob_get_clean();
}

function cg_live_crypto_cards_render_card_item($coin_id, $data) {
    $market = $data['market'][0];
    $prices = array_map(function($p) { return $p[1]; }, $data['chart']['prices'] ?? []);

    $is_up  = $market['price_change_percentage_24h'] >= 0;
    $change = number_format($market['price_change_percentage_24h'], 2);
    $price  = number_format($market['current_price'], 2);

    $context = [
        'image'   => esc_url($market['image']),
        'name'    => esc_html($market['name']),
        'symbol'  => esc_html(strtoupper($market['symbol'])),
        'price'   => $price,
        'change'  => $change,
        'is_up'   => $is_up,
        'prices'  => $prices,
    ];

    cg_live_crypto_cards_load_template('card-item.php', $context);
}

function cg_live_crypto_cards_load_template($template, $vars = []) {
    $file = CG_LIVE_CRYPTO_CARDS_PATH . 'templates/' . $template;
    if (!file_exists($file)) return;

    extract($vars);
    include $file;
}
