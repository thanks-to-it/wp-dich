<?php
/**
 * WP DICH - League Container Dependency Injection Container.
 *
 * @see https://github.com/thephpleague/container
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Thanks to IT
 */

namespace ThanksToIT\WPDICH;

use League\Container\Definition\DefinitionInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToIT\WPDICH\League_Container_DIC' ) ) {

	class League_Container_DIC extends \League\Container\Container implements DIC_Interface {

		/**
		 * {@inheritdoc}
		 */
		public function get( $id ) {
			$object = parent::get( $id );
			if ( is_array( $object ) ) {
				$object = $object[0];
			}
			return $object;
		}

	}
}