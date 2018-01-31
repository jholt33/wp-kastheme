<?php

/**
 * Plugin Name: Dark Mode
 * Plugin URI: https://wordpress.org/plugins/dark-mode/
 * Description: Lets your users make the WordPress admin dashboard darker.
 * Author: Daniel James
 * Author URI: https://www.danieltj.co.uk/
 * Text Domain: dark-mode
 * Version: 1.7
 */

// No thank you
if ( ! defined( 'ABSPATH' ) ) die();

new Dark_Mode;

class Dark_Mode {

	/**
	 * Function which hooks into WordPress Core.
	 * 
	 * @since 1.0
	 * @since 1.1 Changed admin_enqueue_scripts hook to 99 to override admin colour scheme styles.
	 * @since 1.3 Added hook for the Feedback link in the toolbar.
	 * 
	 * @return void
	 */
	public function __construct() {

		add_action('plugins_loaded', array( __CLASS__, 'load_text_domain' ), 10);
		add_action('admin_enqueue_scripts', array( __CLASS__, 'load_dark_mode_css' ), 99);
		add_action('admin_bar_menu', array( __CLASS__, 'add_feedback_link' ), 1250);
		add_action('personal_options', array( __CLASS__, 'add_profile_fields' ), 10);
		add_action('personal_options_update', array( __CLASS__, 'save_profile_fields' ), 10);
		add_action('edit_user_profile_update', array( __CLASS__, 'save_profile_fields' ), 10);

	}

	/**
	 * Load the plugin text domain for l10n.
	 * 
	 * @since 1.0
	 * 
	 * @return void
	 */
	public static function load_text_domain() {

		load_plugin_textdomain( 'dark-mode', false, untrailingslashit( dirname( __FILE__ ) ) . '/languages' );

	}

	/**
	 * Add feedback link to toolbar.
	 * 
	 * Only add the feedback link whilst the user has
	 * Dark Mode turned on. This can be turned off globally
	 * by defining the `DARK_MODE_FEEDBACK` constant to false.
	 * 
	 * @since 1.3
	 * @since 1.4 Updated the toolbar links.
	 * 
	 * @return void
	 */
	public static function add_feedback_link( $wp_admin_bar ) {

		global $wp_admin_bar;
		
		// Get the current user id
		$user_id = get_current_user_id();
		
		// Check the current user has Dark Mode on
		if ( 'on' == get_user_meta( $user_id, 'dark_mode', true ) ) {
		
			// Set the menu class
			$menu_class = 'dark_mode_link';

			// Add an extra class when auto
			if ( true === self::is_dark_mode_auto( $user_id ) && false !== self::is_using_dark_mode( $user_id, true ) ) {

				// Add the extra class
				$menu_class = $menu_class . ' is_auto';

			}

			// Add Dark Mode to the toolbar
			$args = array(
				'id'     => 'dark_mode_link',
				'title'  => _x('Dark Mode', 'Toolbar link text', 'dark-mode'),
				'parent' => 'top-secondary',
				'href'   => admin_url('profile.php'),
				'meta'   => array(
					'class' => $menu_class,
				),
			);

			// Add the link
			$wp_admin_bar->add_node( $args );

			// Should we add the feedback link to the toolbar
			if ( ( ! defined( 'DARK_MODE_FEEDBACK' ) ) || ( defined( 'DARK_MODE_FEEDBACK' ) && false !== DARK_MODE_FEEDBACK ) ) {
	
				// Setup the feedback arguments
				$args = array(
					'id'     => 'dark_mode_feedback',
					'title'  => _x('Feedback', 'Link to GitHub repository', 'dark-mode'),
					'parent' => 'dark_mode_link',
					'href'   => 'https://github.com/danieltj27/Dark-Mode/issues',
					'meta'   => array(
						'class'  => 'dark_mode_feedback',
						'target' => '_blank',
					)
				);

				// Add feedback link
				$wp_admin_bar->add_node($args);

			}

		}

	}

	/**
	 * Checks if a user has Dark Mode enabled.
	 * 
	 * Using this function allows you to check if a specified user
	 * or the current user (default) has Dark Mode enabled. Set the
	 * $check_auto parameter to true to check if it's set to automatically
	 * come between two time frames and we're between the time frame now.
	 * 
	 * @since 1.0
	 * @since 1.6 Major rewrite to properly address automatic Dark Mode.
	 * 
	 * @param string  $user_id    User ID of given person.
	 * @param boolean $check_auto Check for auto mode or not.
	 * 
	 * @return boolean
	 */
	public static function is_using_dark_mode( $user_id = false, $check_auto = false ) {

		// Check we've got a user id
		if ( false === $user_id ) {

			$user_id = get_current_user_id();

		}

		// Check if the user is using Dark Mode
		if ( 'on' == get_user_meta( $user_id, 'dark_mode', true ) ) {

			// Should we check for auto mode
			if ( true === self::is_dark_mode_auto( $user_id ) && true === $check_auto ) {

				// Get the time frames for auto mode
				$auto_start = date_i18n( 'Y-m-d H:i:s', strtotime( get_user_meta( $user_id, 'dark_mode_start', true ) ) );
				$auto_end = date_i18n( 'Y-m-d H:i:s', strtotime( get_user_meta( $user_id, 'dark_mode_end', true ) ) );

				/**
				 * Check if the end time is smaller then the start time
				 * because if the start time is 8pm and the end time is
				 * 6am, without adding 1 day to the end date, the time frame
				 * will actually be 8pm to 6am on the same day which is backwards.
				 */
				if ( $auto_start > $auto_end ) {

					$auto_end = date_i18n( 'Y-m-d H:i:s', strtotime( '+1 day', strtotime( get_user_meta( $user_id, 'dark_mode_end', true ) ) ) );

				}

				// Get the current time
				$current_time = date_i18n( 'Y-m-d H:i:s' );

				// Check the current time is between the start and end time
				if ( $current_time >= $auto_start && $current_time <= $auto_end ) {

					return true;

				}

			} else {

				return true;

			}

		}

		return false;

	}

	/**
	 * Checks if the user is using automatic Dark Mode.
	 * 
	 * This checks if Dark Mode is set to come on automatically
	 * for a given user. This is set to private as it's an extension
	 * of `is_using_dark_mode()` and only checks the auto value is set.
	 * 
	 * @access private
	 * @see (function) is_using_dark_mode
	 * 
	 * @since 1.3
	 * @since 1.5 Access was changed to private.
	 * @since 1.6 Changed default value of user id to false.
	 * 
	 * @param string $user_id User ID
	 * 
	 * @return boolean
	 */
	private static function is_dark_mode_auto( $user_id = false ) {

		// Have we been given a user ID
		if ( false === $user_id ) {

			// Get the current user id
			$user_id = get_current_user_id();

		}

		// Has automatic Dark Mode been turned on
		if ( 'on' == get_user_meta( $user_id, 'dark_mode_auto', true ) ) {

			return true;

		} else {

			return false;

		}

	}

	/**
	 * Add the stylesheet to the dashboard if enabled.
	 * 
	 * @since 1.0
	 * 
	 * @return void
	 */
	public static function load_dark_mode_css() {

		// Get the current user ID
		$user_id = get_current_user_id();

		// Is the current user using Dark Mode?
		if ( false !== self::is_using_dark_mode( $user_id, true ) ) {

			/**
			 * Hook for when Dark Mode is running.
			 * 
			 * This hook is run at the start of Dark Mode initialising
			 * the stylesheet but before it is enqueued.
			 *
			 * @since 1.0
			 */
			do_action('doing_dark_mode');

			/**
			 * Filters the Dark Mode stylesheet URL.
			 *
			 * @since 1.1
			 *
			 * @param string $css_url Default CSS file path for Dark Mode.
			 * 
			 * @return string $css_url
			 */
			$css_url = apply_filters( 'dark_mode_css', plugins_url('dark-mode', 'dark-mode') . '/darkmode.css' );

			// Register the dark mode stylesheet
			wp_register_style('dark_mode', $css_url, array(), '1.6');

			// Enqueue the stylesheet
			wp_enqueue_style('dark_mode');

		}

	}

	/**
	 * Create the HTML markup for the profile setting.
	 * 
	 * @since 1.0
	 * @since 1.3 Added automatic Dark Mode markup.
	 * @since 1.4 Added id attribute to element.
	 * 
	 * @param object $profileuser WP_User object data.
	 * 
	 * @return mixed
	 */
	public static function add_profile_fields( $profileuser ) {

		// Setup a new nonce field for the Dark Mode options
		$dark_mode_nonce = wp_create_nonce('dark_mode_nonce');

		?>
			<tr class="dark-mode user-dark-mode-option" id="dark-mode">
				<th scope="row"><?php _e('Dark Mode', 'dark-mode'); ?></th>
				<td>
					<p>
						<label for="dark_mode">
							<input type="checkbox" id="dark_mode" name="dark_mode" class="dark_mode"<?php if ( 'on' == get_user_meta( $profileuser->data->ID, 'dark_mode', true ) ) : ?> checked="checked"<?php endif; ?> />
							<?php _e('Enable Dark Mode on the admin dashboard', 'dark-mode'); ?>
						</label>
					</p>
					<p>
						<label for="dark_mode_auto">
							<input type="checkbox" id="dark_mode_auto" name="dark_mode_auto" class="dark_mode_auto"<?php if ( 'on' == get_user_meta( $profileuser->data->ID, 'dark_mode_auto', true ) ) : ?> checked="checked"<?php endif; ?> />
							<?php _e('Automatically enable Dark Mode over night between these times:', 'dark-mode'); ?>
						</label>
					</p>
					<p>
						<label>
							<?php _ex('From', 'Time frame starting at', 'dark-mode'); ?> <input type="time" name="dark_mode_start" id="dark_mode_start"<?php if ( false !== get_user_meta( $profileuser->data->ID, 'dark_mode_start', true ) ) : ?> value="<?php echo get_user_meta( $profileuser->data->ID, 'dark_mode_start', true ); ?>"<?php endif; ?> />
						</label>
						<label>
							<?php _ex('To', 'Time frame ending at', 'dark-mode'); ?> <input type="time" name="dark_mode_end" id="dark_mode_end"<?php if ( false !== get_user_meta( $profileuser->data->ID, 'dark_mode_end', true ) ) : ?> value="<?php echo get_user_meta( $profileuser->data->ID, 'dark_mode_end', true ); ?>"<?php endif; ?> />
						</label>
					</p>
					<input type="hidden" name="dark_mode_nonce" id="dark_mode_nonce" value="<?php echo $dark_mode_nonce; ?>" />
				</td>
			</tr>
		<?php

	}

	/**
	 * Save the value of the profile field.
	 * 
	 * @since 1.0
	 * @since 1.3 Added auto Dark Mode settings.
	 * @since 1.7 Added sanitisation to fields not explicitly set.
	 * 
	 * @param string $user_id The user ID of someone.
	 * 
	 * @return void
	 */
	public static function save_profile_fields( $user_id ) {

		// Get the nonce input value
		$dark_mode_nonce = isset( $_POST['dark_mode_nonce'] ) ? sanitize_text_field( $_POST['dark_mode_nonce'] ) : '';

		// Verify the nonce is valid
		if ( wp_verify_nonce( $dark_mode_nonce, 'dark_mode_nonce' ) ) {

			// Set the value of the users choices
			$dark_mode_core = isset ( $_POST['dark_mode'] ) ? 'on' : 'off';
			$dark_mode_auto = isset ( $_POST['dark_mode_auto'] ) ? 'on' : 'off';
			$dark_mode_start = isset ( $_POST['dark_mode_start'] ) ? sanitize_text_field( $_POST['dark_mode_start'] ) : '';
			$dark_mode_end = isset ( $_POST['dark_mode_end'] ) ? sanitize_text_field( $_POST['dark_mode_end'] ) : '';

			// Update the users meta data with the new values
			update_user_meta( $user_id, 'dark_mode', $dark_mode_core );
			update_user_meta( $user_id, 'dark_mode_auto', $dark_mode_auto );
			update_user_meta( $user_id, 'dark_mode_start', $dark_mode_start );
			update_user_meta( $user_id, 'dark_mode_end', $dark_mode_end );

		}
	
	}

}

