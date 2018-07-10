<?php
/**
 * Class PostMetabox
 *
 * @package tenup-primary-category
 */

namespace TenUpPrimaryCategory\Core;

/**
 * Create the metabox at posts; where the user will be able to pick the Primary Category.
 */
class PostMetabox {

	/**
	 * Default constructor.
	 *
	 * @access public
	 */
	public function __construct() {

	}

	/**
	 * Add hooks used by metabox.
	 *
	 * @access public
	 * @return void
	 */
	public function hooks() {
		// Register metabox.
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );

		// Add save_post hook.
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Register the metabox to post page.
	 *
	 * @access public
	 * @return void
	 */
	public function add_metabox() {
		add_meta_box(
			'tenup-primary-category',
			esc_html__( 'Primary Category', 'tenup-primary-category' ),
			array( $this, 'content' ),
			'post',
			'side',
			'core'
		);
	}

	/**
	 * Save the meta data into post.
	 *
	 * @param int $post_id The post ID being saved.
	 * @access public
	 * @return void
	 */
	public function save( $post_id ) {
		// Security checks.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST['tenup_primary_category_nonce'] ) ||
			! wp_verify_nonce( sanitize_key( $_POST['tenup_primary_category_nonce'] ), '_tenup_primary_category_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['tenup_primary_category_the_category'] ) ) {
			update_post_meta( $post_id, 'tenup_primary_category_the_category', sanitize_text_field( wp_unslash( $_POST['tenup_primary_category_the_category'] ) ) );
		}
	}

	/**
	 * Set the metabox HTML markup.
	 *
	 * @access public
	 * @return void
	 */
	public function content() {
		?>
		<?php wp_nonce_field( '_tenup_primary_category_nonce', 'tenup_primary_category_nonce' ); ?>
		<p>
			<select name="tenup_primary_category_the_category" class="postform" style="width: 100%;">
				<option value=""></option>
				<?php echo wp_kses( $this->get_options(), array( 'option' => array( 'value' => array(), 'selected' => array() )) ); ?>
			</select>
		</p>
		<p class="howto" id="new-tag-post_tag-desc">
			<?php esc_html_e( 'Pick the primary category.', 'tenup-primary-category' ); ?>
		</p>
		<?php
	}

	/**
	 * Return the metabox options markup.
	 *
	 * This markup is used to allow users select the primary category.
	 *
	 * @access public
	 * @return string
	 */
	public function get_options() {
		// Get current post categories.
		$categories = get_the_category();

		// Store the options markup.
		$options = array();

		// Get stored current category.
		$current = get_post_meta( get_the_ID(), 'tenup_primary_category_the_category', true );

		// Loop the categories.
		foreach ( $categories as $category ) {
			// Check if it's selected.
			$selected = $current == $category->term_id ? ' selected="selected" ' : ' ';
			// Add the new item to category.
			$options[] = '<option' . $selected . 'value="' . esc_attr( $category->term_id ) . '">' . esc_html( $category->name ) . '</option>';
		}

		// Return the markup.
		return implode( '', $options );
	}
}
