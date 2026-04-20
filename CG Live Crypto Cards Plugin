<?php
/**
 * Plugin Name: CG Live Crypto Cards
 * Description: Live crypto price cards with animated charts using CoinGecko API.
 * Version: 1.1
 * Author: Coin Gazette
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';

function cg_live_cards_defaults() {
    return [
        'coins'          => ['bitcoin','ethereum','solana','tether'],
        'layout'         => 'grid', // grid | list | ticker
        'dark_mode'      => 'auto', // auto | light | dark
        'color_up'       => '#0f9d58',
        'color_down'     => '#d93025',
        'card_bg_light'  => '#ffffff',
        'card_bg_dark'   => '#050711',
        'card_border'    => '#e5e5e5',
        'text_light'     => '#111111',
        'text_dark'      => '#f5f5f5',
        'cache_seconds'  => 120,
    ];
}

function cg_live_cards_get_settings() {
    $defaults = cg_live_cards_defaults();
    $saved    = get_option('cg_live_cards_settings', []);
    $merged   = wp_parse_args($saved, $defaults);
    $merged['coins'] = !empty($merged['coins']) ? (array) $merged['coins'] : $defaults['coins'];
    return $merged;
}

// Assets
add_action('wp_enqueue_scripts', function () {
    $settings = cg_live_cards_get_settings();

    wp_enqueue_style(
        'cg-live-cards-style',
        plugin_dir_url(__FILE__) . 'assets/style.css',
        [],
        '1.1'
    );

    wp_enqueue_script(
        'cg-live-cards-script',
        plugin_dir_url(__FILE__) . 'assets/script.js',
        [],
        '1.1',
        true
    );

    wp_localize_script('cg-live-cards-script', 'cgLiveCards', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'settings' => [
            'coins'         => $settings['coins'],
            'layout'        => $settings['layout'],
            'dark_mode'     => $settings['dark_mode'],
            'color_up'      => $settings['color_up'],
            'color_down'    => $settings['color_down'],
            'card_bg_light' => $settings['card_bg_light'],
            'card_bg_dark'  => $settings['card_bg_dark'],
            'card_border'   => $settings['card_border'],
            'text_light'    => $settings['text_light'],
            'text_dark'     => $settings['text_dark'],
        ],
    ]);
});

// AJAX with caching
add_action('wp_ajax_cg_get_coin_data', 'cg_get_coin_data');
add_action('wp_ajax_nopriv_cg_get_coin_data', 'cg_get_coin_data');

function cg_get_coin_data() {
    if (!isset($_GET['coin'])) wp_send_json_error('Missing coin');

    $coin     = sanitize_text_field($_GET['coin']);
    $settings = cg_live_cards_get_settings();
    $cache_key = 'cg_live_cards_' . $coin;

    $cached = get_transient($cache_key);
    if ($cached) {
        wp_send_json($cached);
    }

    $market = wp_remote_get("https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids=$coin&price_change_percentage=24h");
    $chart  = wp_remote_get("https://api.coingecko.com/api/v3/coins/$coin/market_chart?vs_currency=usd&days=1&interval=hourly");

    if (is_wp_error($market) || is_wp_error($chart)) {
        wp_send_json_error('API error');
    }

    $payload = [
        'market' => json_decode(wp_remote_retrieve_body($market), true),
        'chart'  => json_decode(wp_remote_retrieve_body($chart), true),
    ];

    set_transient($cache_key, $payload, (int) $settings['cache_seconds']);
    wp_send_json($payload);
}

// Shortcode
add_shortcode('cg_live_crypto_cards', function () {
    $settings = cg_live_cards_get_settings();
    $layout   = esc_attr($settings['layout']);
    $dark     = esc_attr($settings['dark_mode']);

    return '<div id="cg-live-cards-wrapper" class="cg-layout-' . $layout . '" data-dark-mode="' . $dark . '">
                <div id="cg-live-cards"></div>
            </div>';
});
