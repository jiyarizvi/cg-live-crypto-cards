<?php
if (!defined('ABSPATH')) exit;

function cg_live_crypto_cards_get_cache_key($coin) {
    return CG_LIVE_CRYPTO_CARDS_PREFIX . 'coin_' . sanitize_key($coin);
}

function cg_live_crypto_cards_get_cached_coin($coin) {
    $key = cg_live_crypto_cards_get_cache_key($coin);
    return get_transient($key);
}

function cg_live_crypto_cards_set_cached_coin($coin, $data) {
    $settings = cg_live_crypto_cards_get_settings();
    $key      = cg_live_crypto_cards_get_cache_key($coin);
    set_transient($key, $data, (int) $settings['cache_seconds']);
}
