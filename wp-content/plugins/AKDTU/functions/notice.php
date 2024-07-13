<?php

/**
 * @file Functionality related to the display of notices on the admin controlpanel on the website
 */

# Class to write admin notices
class AKDTU_notice
{
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
	public function __construct(string $type, string $message, bool $is_dismissible = true)
	{
		$this->message = $message;
		$this->type = $type;
		$this->is_dismissible = $is_dismissible;

		add_action('admin_notices', [$this, 'render']);
	}

	/**
	 * Displays notice on the admin screen.
	 *
	 * @return void
	 */
	public function render()
	{
		echo "<div class=\"notice notice-{esc_html($this->type)} {($this->is_dismissible ? 'is-dismissible' : '')}\"><p>{esc_html($this->message)}</p></div>";
	}
}
