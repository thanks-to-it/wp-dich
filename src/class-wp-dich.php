<?php
/**
 * WP DICH
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

if ( ! class_exists( 'Thanks_To_IT\WP_DICH\WP_DICH' ) ) {

	class WP_DICH {

		public $di_container;
		public $callbacks = array();

		/**
		 * constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param ContainerInterface $di_container
		 */
		public function __construct( ContainerInterface $di_container ) {
			$this->di_container = $di_container;
		}

		/**
		 * get_object_alias.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param callable $function_to_add
		 *
		 * @return bool|mixed
		 */
		private function get_object_alias( $function_to_add ) {
			if (
				! is_array( $function_to_add ) ||
				! is_string( $function_to_add[0] ) ||
				is_object( $function_to_add[0] )
			) {
				return false;
			}

			return $function_to_add[0];
		}

		/**
		 * get_function_to_add.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param callable $callback
		 *
		 * @return array
		 */
		private function get_callback( $callback ) {
			$object_alias          = $this->get_object_alias( $callback );
			$final_function_to_add = $callback;
			if (
				false !== $object_alias &&
				$this->di_container->has( $object_alias )
			) {
				$object                = $this->di_container->get( $object_alias );
				$final_function_to_add = array( $object, $callback[1] );
			} else {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					if ( is_array( $callback ) && is_string( $callback[0] ) ) {
						error_log( sprintf( 'WP_DICH Container doesn\'t have a value stored for the "%s" key.', $object_alias ) );
					}
				}
			}
			return $final_function_to_add;
		}

		/**
		 * add_action.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $tag
		 * @param callable $function_to_add
		 * @param int $priority
		 * @param int $accepted_args
		 *
		 */
		public function add_action( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
			$this->remove_from_callbacks( $tag, $function_to_add, $priority );
			add_action( $tag, function () use ( $tag, $function_to_add, $priority ) {
				if ( $this->need_to_remove( $function_to_add, $tag, $priority ) ) {
					return;
				}
				$function_to_add = $this->get_callback( $function_to_add );
				call_user_func_array( $function_to_add, func_get_args() );
			}, $priority, $accepted_args );
		}

		/**
		 * add_filter.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $tag
		 * @param callable $function_to_add
		 * @param int $priority
		 * @param int $accepted_args
		 *
		 * @return \WP_Error|boolean
		 */
		public function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
			$this->remove_from_callbacks( $tag, $function_to_add, $priority );
			return add_filter( $tag, function () use ( $tag, $function_to_add, $priority ) {
				if ( $this->need_to_remove( $function_to_add, $tag, $priority ) ) {
					return func_get_args()[0];
				}
				$function_to_add = $this->get_callback( $function_to_add );
				return call_user_func_array( $function_to_add, func_get_args() );
			}, $priority, $accepted_args );
		}

		/**
		 * need_to_remove.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $function_to_add
		 * @param $tag
		 *
		 * @param int $priority
		 *
		 * @return bool
		 */
		private function need_to_remove( $function_to_add, $tag, $priority = 10 ) {
			$function_to_add   = $this->modify_callback_if_string( $function_to_add );
			$function_to_add[] = $priority;
			return in_array( $function_to_add, $this->callbacks[ $tag ] );
		}

		/**
		 * remove_from_callbacks.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $tag
		 * @param $function_to_add
		 */
		private function remove_from_callbacks( $tag, $function_to_add, $priority ) {
			! isset( $this->callbacks[ $tag ] ) ? $this->callbacks[ $tag ] = array() : $this->callbacks[ $tag ];
			$function_to_add   = $this->modify_callback_if_string( $function_to_add );
			$function_to_add[] = $priority;
			$found             = array_search( $function_to_add, $this->callbacks[ $tag ] );
			if ( false !== $found ) {
				unset( $this->callbacks[ $tag ][ $found ] );
			}
		}

		/**
		 * modify_callback_if_string.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $callback
		 *
		 * @return array|string
		 */
		private function modify_callback_if_string( $callback ) {
			if ( is_string( $callback ) ) {
				$callback = array( $callback );
			}
			return $callback;
		}

		/**
		 * remove_filter.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $tag
		 * @param callable $function_to_remove
		 * @param int $priority
		 */
		public function remove_filter( $tag, $function_to_remove, $priority = 10 ) {
			$function_to_remove        = $this->modify_callback_if_string( $function_to_remove );
			$function_to_remove[]      = $priority;
			$this->callbacks[ $tag ][] = $function_to_remove;
			$this->callbacks[ $tag ]   = array_unique( $this->callbacks[ $tag ], SORT_REGULAR );
		}

		/**
		 * remove_filter.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $tag
		 * @param callable $function_to_remove
		 * @param int $priority
		 */
		public function remove_action( $tag, $function_to_remove, $priority = 10 ) {
			$this->remove_filter( $tag, $function_to_remove, $priority );
		}

	}
}