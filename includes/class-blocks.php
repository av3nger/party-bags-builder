<?php
/**
 * Blocks class
 *
 * Handles Gutenberg block registration and rendering.
 *
 * @package PartyBagBuilder
 */

namespace PBB;

defined( 'ABSPATH' ) || exit;

/**
 * Blocks class.
 */
final class Blocks {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_blocks' ) );
	}

	/**
	 * Register all blocks from metadata collection.
	 */
	public function register_blocks(): void {
		wp_register_block_types_from_metadata_collection(
			PBB_PLUGIN_DIR . 'build',
			PBB_PLUGIN_DIR . 'build/blocks-manifest.php'
		);
	}
}
