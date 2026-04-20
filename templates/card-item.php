<?php
if (!defined('ABSPATH')) exit;
?>
<div class="cg-card">
    <div class="cg-left">
        <div class="cg-card-header">
            <img src="<?php echo $image; ?>" alt="<?php echo $symbol; ?>">
            <div class="cg-card-title"><?php echo $name; ?> (<?php echo $symbol; ?>)</div>
        </div>
        <div class="cg-price">$<?php echo $price; ?></div>
        <div class="cg-change <?php echo $is_up ? 'cg-up' : 'cg-down'; ?>">
            <?php echo $is_up ? '+' : ''; ?><?php echo $change; ?>%
        </div>
    </div>
    <div class="cg-chart-wrapper"
         data-prices="<?php echo esc_attr(json_encode($prices)); ?>"
         data-is-up="<?php echo $is_up ? '1' : '0'; ?>">
    </div>
</div>
