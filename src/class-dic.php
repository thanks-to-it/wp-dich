<?php
/**
 * WP DICH - Dependency Injection Container
 *
 * @see https://carlalexander.ca/dependency-injection-wordpress/
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Thanks to IT
 */

namespace Thanks_To_IT\WP_DICH;

use Psr\Container\ContainerInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Thanks_To_IT\WP_DICH\DIC' ) ) {

	class DIC implements \ArrayAccess, ContainerInterface {
		/**
		 * Values stored inside the container.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @var array
		 */
		private $values;

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param array $values
		 */
		public function __construct( array $values = array() ) {
			$this->values = $values;
		}

		/**
		 * {@inheritdoc}
		 */
		public function get( $id ) {
			return $this[ $id ];
		}

		/**
		 * {@inheritdoc}
		 */
		function has( $id ) {
			return $this->offsetExists( $id );
		}

		/**
		 * Configure the container using the given container configuration objects.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param array $configurations
		 */
		public function configure( array $configurations ) {
			foreach ( $configurations as $configuration ) {
				$configuration->modify( $this );
			}
		}

		/**
		 * Checks if there's a value in the container for the given key.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param mixed $key
		 *
		 * @return bool
		 */
		public function offsetExists( $key ) {
			return array_key_exists( $key, $this->values );
		}

		/**
		 * Get a value from the container.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param mixed $key
		 *
		 * @return mixed|WP_Error
		 */
		public function offsetGet( $key ) {
			if ( ! array_key_exists( $key, $this->values ) ) {
				return new \WP_Error( 'no_value_found', sprintf( 'Container doesn\'t have a value stored for the "%s" key.', $key ) );
			}

			return $this->values[ $key ] instanceof \Closure ? $this->values[$key]( $this ) : $this->values[ $key ];
		}

		/**
		 * Sets a value inside of the container.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param mixed $key
		 * @param mixed $value
		 */
		public function offsetSet( $key, $value ) {
			$this->values[ $key ] = $value;
		}

		/**
		 * Unset the value in the container for the given key.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param mixed $key
		 */
		public function offsetUnset( $key ) {
			unset( $this->values[ $key ] );
		}

		/**
		 * Creates a closure used for creating a service using the given callable.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param \Closure $closure
		 *
		 * @return \Closure
		 */
		public function service( \Closure $closure ) {
			return function ( DIC $container ) use ( $closure ) {
				static $object;

				if ( null === $object ) {
					$object = $closure( $container );
				}

				return $object;
			};
		}
	}
}