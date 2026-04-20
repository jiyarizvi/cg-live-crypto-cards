<?php
if (!defined('ABSPATH')) exit;

function cg_live_crypto_cards_fetch_coin_data($coin) {
    $market_url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids=$coin&price_change_percentage=24h";
    $chart_url  = "https://api.coingecko.com/api/v3/coins/$coin/market_chart?vs_currency=usd&days=1&interval=hourly";

    $market = wp_remote_get($market_url);
    $chart  = wp_remote_get($chart_url);

    if (is_wp_error($market) || is_wp_error($chart)) {
        return null;
    }

    return [
        'market' => json_decode(wp_remote_retrieve_body($market), true),
        'chart'  => json_decode(wp_remote_retrieve_body($chart), true),
    ];
}

function cg_live_crypto_cards_get_coin_data($coin) {
    $cached = cg_live_crypto_cards_get_cached_coin($coin);
    if ($cached) return $cached;

    $data = cg_live_crypto_cards_fetch_coin_data($coin);
    if ($data && !empty($data['market'][0])) {
        cg_live_crypto_cards_set_cached_coin($coin, $data);
    }

    return $data;
}
