<?php
/**
 * Review Notice
 * Render review notice template on admin dashboard
 *
 * @package WP Dark Mode
 * @since 2.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit();
?>
<p>
	<?php //phpcs:ignore ?>
	<?php esc_html_e( 'Hi there, it seems like WP Dark Mode is bringing you some value, and that is pretty awesome! Can you please show us some love and rate WP Dark Mode on WordPress? It will take two minutes of your time, and will really help us spread the world.', 'wp-dark-mode' ); ?>
</p>

<div class="notice-actions">
	<a class="hide_notice" data-value="hide_notice" href="https://wordpress.org/support/plugin/wp-dark-mode/reviews/?filter=5#new-post" target="_blank">
		<?php esc_html_e( "I'd love to help :)", 'wp-dark-mode' ); ?>
	</a>
	<a href="#" class="remind_later"><?php esc_html__( 'Not this time', 'wp-dark-mode' ); ?></a>
	<a href="#" class="hide_notice" data-value="hide_notice"><?php esc_html__( 'I\'ve already rated you', 'wp-dark-mode' ); ?></a>
</div>

<div class="notice-overlay-wrap">
	<div class="notice-overlay">
		<h4><?php esc_html__( 'Would you like us to remind you about this later?', 'wp-dark-mode' ); ?></h4>

		<div class="notice-overlay-actions">
			<a href="#" data-value="3"><?php esc_html__( 'Remind me in 3 days', 'wp-dark-mode' ); ?></a>
			<a href="#" data-value="10"><?php esc_html__( 'Remind me in 10 days', 'wp-dark-mode' ); ?></a>
			<a href="#" data-value="hide_notice"><?php esc_html__( 'Don\'t remind me about this', 'wp-dark-mode' ); ?></a>
		</div>

		<button type="button" class="close-notice">&times;</button>
	</div>
</div>