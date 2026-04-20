<?php
if (!defined('ABSPATH')) exit;

function cg_live_crypto_cards_defaults() {
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

function cg_live_crypto_cards_get_settings() {
    $defaults = cg_live_crypto_cards_defaults();
    $saved    = get_option('cg_live_crypto_cards_settings', []);
    $merged   = wp_parse_args($saved, $defaults);
    $merged['coins'] = !empty($merged['coins']) ? (array) $merged['coins'] : $defaults['coins'];
    return $merged;
}
