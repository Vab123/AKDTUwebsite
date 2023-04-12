<?php
## Class to write admin notices
class AKDTU_notice {
	/**
	 * Message to be displayed in a warning.
	 *
	 * @var string\wp-admin\users.php
	 */
	private string $message;

	/**
	 * Type of message: error, warning, success, info.
	 *
	 * @var string
	 */
	private string $type;

	/**
	 * Flag, whether message should be dismissable.
	 *
	 * @var bool
	 */
	private bool $is_dismissible;

	/**
	 * Initialize class.
	 *
	 * @param string $type Type of message: error, warning, success, info.
	 * @param string $message Message to be displayed in a warning.
	 * @param bool $is_dismissible Flag, whether message should be dismissable.
	 */
	public function __construct(string $type, string $message, bool $is_dismissible = true) {
		$this->message = $message;
		$this->type = $type;
		$this->is_dismissible = $is_dismissible;

		add_action('admin_notices', array($this, 'render'));
	}

	/**
	 * Displays notice on the admin screen.
	 *
	 * @return void
	 */
	public function render() {
		printf('<div class="notice notice-%s %s"><p>%s</p></div>', esc_html($this->type), ($this->is_dismissible ? 'is-dismissible' : ''), esc_html($this->message));
	}
}
