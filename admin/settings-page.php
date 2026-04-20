<?php
if (!defined('ABSPATH')) exit;

add_action('admin_menu', function () {
    add_options_page(
        'CG Live Crypto Cards',
        'CG Live Cards',
        'manage_options',
        'cg-live-cards',
        'cg_live_cards_settings_page'
    );
});

function cg_live_cards_settings_page() {
    if (!current_user_can('manage_options')) return;

    $defaults = cg_live_cards_defaults();
    $settings = cg_live_cards_get_settings();

    if (isset($_POST['cg_save'])) {
        check_admin_referer('cg_live_cards_save');

        $coins = array_filter(array_map('trim', explode(',', $_POST['cg_coins'])));
        $settings = [
            'coins'          => $coins,
            'layout'         => sanitize_text_field($_POST['cg_layout']),
            'dark_mode'      => sanitize_text_field($_POST['cg_dark_mode']),
            'color_up'       => sanitize_hex_color($_POST['cg_color_up']),
            'color_down'     => sanitize_hex_color($_POST['cg_color_down']),
            'card_bg_light'  => sanitize_hex_color($_POST['cg_card_bg_light']),
            'card_bg_dark'   => sanitize_hex_color($_POST['cg_card_bg_dark']),
            'card_border'    => sanitize_hex_color($_POST['cg_card_border']),
            'text_light'     => sanitize_hex_color($_POST['cg_text_light']),
            'text_dark'      => sanitize_hex_color($_POST['cg_text_dark']),
            'cache_seconds'  => (int) $_POST['cg_cache_seconds'],
        ];

        update_option('cg_live_cards_settings', $settings);
        echo '<div class="updated"><p>Saved!</p></div>';
    }

    $coins_str = esc_attr(implode(',', $settings['coins']));
    ?>
    <div class="wrap">
        <h1>CG Live Crypto Cards</h1>
        <form method="post">
            <?php wp_nonce_field('cg_live_cards_save'); ?>

            <h2>Coins</h2>
            <p><label>CoinGecko IDs (comma separated)</label></p>
            <input type="text" name="cg_coins" value="<?php echo $coins_str; ?>" style="width: 400px;">

            <h2 style="margin-top:20px;">Layout</h2>
            <select name="cg_layout">
                <option value="grid"  <?php selected($settings['layout'], 'grid'); ?>>Grid</option>
                <option value="list"  <?php selected($settings['layout'], 'list'); ?>>List</option>
                <option value="ticker"<?php selected($settings['layout'], 'ticker'); ?>>Ticker</option>
            </select>

            <h2 style="margin-top:20px;">Dark Mode</h2>
            <select name="cg_dark_mode">
                <option value="auto" <?php selected($settings['dark_mode'], 'auto'); ?>>Auto (prefers-color-scheme)</option>
                <option value="light"<?php selected($settings['dark_mode'], 'light'); ?>>Light only</option>
                <option value="dark" <?php selected($settings['dark_mode'], 'dark'); ?>>Dark only</option>
            </select>

            <h2 style="margin-top:20px;">Colors</h2>
            <p>
                Up color: <input type="text" name="cg_color_up" value="<?php echo esc_attr($settings['color_up']); ?>" size="8">
                &nbsp;Down color: <input type="text" name="cg_color_down" value="<?php echo esc_attr($settings['color_down']); ?>" size="8">
            </p>
            <p>
                Card BG (light): <input type="text" name="cg_card_bg_light" value="<?php echo esc_attr($settings['card_bg_light']); ?>" size="8">
                &nbsp;Card BG (dark): <input type="text" name="cg_card_bg_dark" value="<?php echo esc_attr($settings['card_bg_dark']); ?>" size="8">
            </p>
            <p>
                Card border: <input type="text" name="cg_card_border" value="<?php echo esc_attr($settings['card_border']); ?>" size="8">
            </p>
            <p>
                Text (light): <input type="text" name="cg_text_light" value="<?php echo esc_attr($settings['text_light']); ?>" size="8">
                &nbsp;Text (dark): <input type="text" name="cg_text_dark" value="<?php echo esc_attr($settings['text_dark']); ?>" size="8">
            </p>

            <h2 style="margin-top:20px;">Caching</h2>
            <p>
                Cache duration (seconds): 
                <input type="number" name="cg_cache_seconds" value="<?php echo esc_attr($settings['cache_seconds']); ?>" min="30" step="30">
            </p>

            <p style="margin-top:20px;">
                <button class="button button-primary" name="cg_save">Save Settings</button>
            </p>
        </form>
    </div>
    <?php
}
