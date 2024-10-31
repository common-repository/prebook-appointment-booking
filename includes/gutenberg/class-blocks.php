<?php

/**
 * Prebook Gutenberg Block
 *
 * @since 1.2.0
 * @package EasyVideoReviews
 */

namespace EasyVideoReviews;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! class_exists( __NAMESPACE__ . '\Blocks' ) ) {

	/**
	 * Prebook Gutenberg Block
	 *
	 * @since 1.2.0
	 */
	class Blocks extends \Prebook\Base\Controller {


		/**
		 * Actions
		 *
		 * @return void
		 */
		public function actions() {
			add_filter( 'block_categories_all', [ $this, 'register_block_category' ], 10, 2 );
			add_action( 'init', [ $this, 'register_blocks' ] );
		}


		/**
		 * Register Prebook Gutenberg Block Category
		 *
		 * @param array $categories Block categories.
		 * @return mixed
		 */
		public function register_block_category( $categories ) {

			return array_merge(
				$categories,
				[
					[
						'slug'  => 'prebook',
						'title' => esc_html__( 'Prebook', 'prebook' ),
						'icon'  => 'microphone',
					],
				]
			);
		}


		/**
		 * Register Prebook Gutenberg Blocks
		 *
		 * @return void
		 */
		public function register_blocks() {
			if ( ! function_exists( 'register_block_type' ) ) {
				return;
			}

			wp_register_script(
				'prebook-editor-script',
				plugin_dir_url( __FILE__ ) . '/block/main.js',
				[ 'react', 'wp-block-editor', 'wp-components', 'wp-element' ],
				PREBOOK_VERSION,
				true
			);

			// Registers button block.
			register_block_type( 'prebook/button', [ 'editor_script' => 'prebook-editor-script' ] );
			register_block_type( 'prebook/form', [ 'editor_script' => 'prebook-editor-script' ] );
		}
	}

	// Init the class.
	Blocks::init();
}
