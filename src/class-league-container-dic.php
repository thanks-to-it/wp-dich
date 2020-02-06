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

namespace Thanks_To_IT\WP_DICH;

use League\Container\Definition\DefinitionInterface;
use Psr\Container\ContainerInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Thanks_To_IT\WP_DICH\League_Container_DIC' ) ) {

	class League_Container_DIC extends \League\Container\Container implements ContainerInterface {

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

		/**
		 * {@inheritdoc}
		 */
		public function has( $id ) {
			return parent::has( $id );
		}

	}
}