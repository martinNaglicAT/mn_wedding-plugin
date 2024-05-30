<?php

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="message message-reply<?php echo $mine; ?>">
    <span class="author"><?php echo esc_html($username); ?></span>
    <p><?php echo esc_html($message['content']) ?></p>
    <div class="datetime"><?php echo $time_format; ?></div>
</div>