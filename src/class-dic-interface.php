<?php
/**
 * WP DICH - Dependency Injection Container Interface
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Thanks to IT
 */

namespace ThanksToIT\WPDICH;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! interface_exists( 'ThanksToIT\WPDICH\DIC_Interface' ) ) {

	interface DIC_Interface {

		/**
		 * Finds an entry of the container by its identifier and returns it.
		 *
		 * @param string $id Identifier of the entry to look for.
		 *
		 * @return mixed Entry.
		 */
		public function get( $id );
	}
}