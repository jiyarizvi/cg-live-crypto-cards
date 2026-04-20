<?php
if (!defined('ABSPATH')) exit;

function cg_live_crypto_cards_shortcode($atts) {
    return cg_live_crypto_cards_render_wrapper();
}
add_shortcode('cg_live_crypto_cards', 'cg_live_crypto_cards_shortcode');
