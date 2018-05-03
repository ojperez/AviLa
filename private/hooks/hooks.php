<?php
require_once 'hook.class.php';
global $a_filter, $a_actions, $a_current_filter;


if ( $a_filter ) {
	$a_filter = A_Hook::build_preinitialized_hooks( $a_filter );
} else {
	$a_filter = array();
}

if ( ! isset( $a_actions ) )
	$a_actions = array();

if ( ! isset( $a_current_filter ) )
	$a_current_filter = array();



function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
	global $a_filter;
	if ( ! isset( $a_filter[ $tag ] ) ) {
		$a_filter[ $tag ] = new A_Hook();
	}
	$a_filter[ $tag ]->add_filter( $tag, $function_to_add, $priority, $accepted_args );
	return true;
}

function has_filter($tag, $function_to_check = false) {
	global $a_filter;

	if ( ! isset( $a_filter[ $tag ] ) ) {
		return false;
	}

	return $a_filter[ $tag ]->has_filter( $tag, $function_to_check );
}

function apply_filters( $tag, $value ) {
	global $a_filter, $a_current_filter;

	$args = array();

	// Do 'all' actions first.
	if ( isset($a_filter['all']) ) {
		$a_current_filter[] = $tag;
		$args = func_get_args();
		_a_call_all_hook($args);
	}

	if ( !isset($a_filter[$tag]) ) {
		if ( isset($a_filter['all']) )
			array_pop($a_current_filter);
		return $value;
	}

	if ( !isset($a_filter['all']) )
		$a_current_filter[] = $tag;

	if ( empty($args) )
		$args = func_get_args();

	// don't pass the tag name to WP_Hook
	array_shift( $args );

	$filtered = $a_filter[ $tag ]->apply_filters( $value, $args );

	array_pop( $a_current_filter );

	return $filtered;
}

function apply_filters_ref_array($tag, $args) {
	global $a_filter, $a_current_filter;

	// Do 'all' actions first
	if ( isset($a_filter['all']) ) {
		$a_current_filter[] = $tag;
		$all_args = func_get_args();
		_a_call_all_hook($all_args);
	}

	if ( !isset($a_filter[$tag]) ) {
		if ( isset($a_filter['all']) )
			array_pop($a_current_filter);
		return $args[0];
	}

	if ( !isset($a_filter['all']) )
		$a_current_filter[] = $tag;

	$filtered = $a_filter[ $tag ]->apply_filters( $args[0], $args );

	array_pop( $a_current_filter );

	return $filtered;
}

function remove_filter( $tag, $function_to_remove, $priority = 10 ) {
	global $a_filter;

	$r = false;
	if ( isset( $a_filter[ $tag ] ) ) {
		$r = $a_filter[ $tag ]->remove_filter( $tag, $function_to_remove, $priority );
		if ( ! $a_filter[ $tag ]->callbacks ) {
			unset( $a_filter[ $tag ] );
		}
	}

	return $r;
}

function remove_all_filters( $tag, $priority = false ) {
	global $a_filter;

	if ( isset( $a_filter[ $tag ]) ) {
		$a_filter[ $tag ]->remove_all_filters( $priority );
		if ( ! $a_filter[ $tag ]->has_filters() ) {
			unset( $a_filter[ $tag ] );
		}
	}

	return true;
}

function current_filter() {
	global $a_current_filter;
	return end( $a_current_filter );
}


function current_action() {
	return current_filter();
}

function doing_filter( $filter = null ) {
	global $a_current_filter;

	if ( null === $filter ) {
		return ! empty( $a_current_filter );
	}

	return in_array( $filter, $a_current_filter );
}


function doing_action( $action = null ) {
	return doing_filter( $action );
}


function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
	return add_filter($tag, $function_to_add, $priority, $accepted_args);
}


function do_action($tag, $arg = '') {
	global $a_filter, $a_actions, $a_current_filter;

	if ( ! isset($a_actions[$tag]) )
		$a_actions[$tag] = 1;
	else
		++$a_actions[$tag];

	// Do 'all' actions first
	if ( isset($a_filter['all']) ) {
		$a_current_filter[] = $tag;
		$all_args = func_get_args();
		_a_call_all_hook($all_args);
	}

	if ( !isset($a_filter[$tag]) ) {
		if ( isset($a_filter['all']) )
			array_pop($a_current_filter);
		return;
	}

	if ( !isset($a_filter['all']) )
		$a_current_filter[] = $tag;

	$args = array();
	if ( is_array($arg) && 1 == count($arg) && isset($arg[0]) && is_object($arg[0]) ) // array(&$this)
		$args[] =& $arg[0];
	else
		$args[] = $arg;
	for ( $a = 2, $num = func_num_args(); $a < $num; $a++ )
		$args[] = func_get_arg($a);

	$a_filter[ $tag ]->do_action( $args );

	array_pop($a_current_filter);
}

function did_action($tag) {
	global $a_actions;

	if ( ! isset( $a_actions[ $tag ] ) )
		return 0;

	return $a_actions[$tag];
}

function do_action_ref_array($tag, $args) {
	global $a_filter, $a_actions, $a_current_filter;

	if ( ! isset($a_actions[$tag]) )
		$a_actions[$tag] = 1;
	else
		++$a_actions[$tag];

	// Do 'all' actions first
	if ( isset($a_filter['all']) ) {
		$a_current_filter[] = $tag;
		$all_args = func_get_args();
		_a_call_all_hook($all_args);
	}

	if ( !isset($a_filter[$tag]) ) {
		if ( isset($a_filter['all']) )
			array_pop($a_current_filter);
		return;
	}

	if ( !isset($a_filter['all']) )
		$a_current_filter[] = $tag;

	$a_filter[ $tag ]->do_action( $args );

	array_pop($a_current_filter);
}


function has_action($tag, $function_to_check = false) {
	return has_filter($tag, $function_to_check);
}

function remove_action( $tag, $function_to_remove, $priority = 10 ) {
	return remove_filter( $tag, $function_to_remove, $priority );
}

function remove_all_actions($tag, $priority = false) {
	return remove_all_filters($tag, $priority);
}


function apply_filters_deprecated( $tag, $args, $version, $replacement = false, $message = null ) {
	if ( ! has_filter( $tag ) ) {
		return $args[0];
	}

	_deprecated_hook( $tag, $version, $replacement, $message );

	return apply_filters_ref_array( $tag, $args );
}
function do_action_deprecated( $tag, $args, $version, $replacement = false, $message = null ) {
	if ( ! has_action( $tag ) ) {
		return;
	}

	_deprecated_hook( $tag, $version, $replacement, $message );

	do_action_ref_array( $tag, $args );
}

//
// Functions for handling plugins.
//

function a_normalize_path( $path ) {
	$path = str_replace( '\\', '/', $path );
	$path = preg_replace( '|(?<=.)/+|', '/', $path );
	if ( ':' === substr( $path, 1, 1 ) ) {
		$path = ucfirst( $path );
	}
	return $path;
}
function plugin_basename( $file ) {
	global $a_plugin_paths;

	// $a_plugin_paths contains normalized paths.
	$file = a_normalize_path( $file );

	arsort( $a_plugin_paths );
	foreach ( $a_plugin_paths as $dir => $realdir ) {
		if ( strpos( $file, $realdir ) === 0 ) {
			$file = $dir . substr( $file, strlen( $realdir ) );
		}
	}

	$plugin_dir = a_normalize_path(_PLUGIN_DIR );
//	$mu_plugin_dir = a_normalize_path( WPMU_PLUGIN_DIR );

//	$file = preg_replace('#^' . preg_quote($plugin_dir, '#') . '/|^' . preg_quote($mu_plugin_dir, '#') . '/#','',$file); // get relative path from plugins dir
        $file = preg_replace('#^' . preg_quote($plugin_dir, '#') . '/#','',$file); 
	$file = trim($file, '/');
	return $file;
}


function a_register_plugin_realpath( $file ) {
	global $a_plugin_paths;

	// Normalize, but store as static to avoid recalculation of a constant value
	static $a_plugin_path = null;
	if ( ! isset( $a_plugin_path ) ) {
		$a_plugin_path   = a_normalize_path( _PLUGIN_DIR   );
//		$wpmu_plugin_path = a_normalize_path( WPMU_PLUGIN_DIR );
	}

	$plugin_path = a_normalize_path( dirname( $file ) );
	$plugin_realpath = a_normalize_path( dirname( realpath( $file ) ) );

	if ( $plugin_path === $a_plugin_path ) {
		return false;
	}

	if ( $plugin_path !== $plugin_realpath ) {
		$a_plugin_paths[ $plugin_path ] = $plugin_realpath;
	}
	return true;
}


function plugin_dir_path( $file ) {
	return trailingslashit( dirname( $file ) );
}


function plugin_dir_url( $file ) {
	return trailingslashit( plugins_url( '', $file ) );
}


function register_activation_hook($file, $function) {
	$file = plugin_basename($file);
	add_action('activate_' . $file, $function);
}


function register_deactivation_hook($file, $function) {
	$file = plugin_basename($file);
	add_action('deactivate_' . $file, $function);
}


function register_uninstall_hook( $file, $callback ) {
	if ( is_array( $callback ) && is_object( $callback[0] ) ) {
		add_alert( __FUNCTION__.__( 'Only a static class method or function can be used in an uninstall hook.' ), 'danger' );
		return;
	}

	$uninstallable_plugins = (array) get_option('uninstall_plugins');
	$uninstallable_plugins[plugin_basename($file)] = $callback;

	update_option('uninstall_plugins', $uninstallable_plugins);
}


function _a_call_all_hook($args) {
	global $a_filter;

	$a_filter['all']->do_all_hook( $args );
}


function _a_filter_build_unique_id($tag, $function, $priority) {
	global $a_filter;
	static $filter_id_count = 0;

	if ( is_string($function) )
		return $function;

	if ( is_object($function) ) {
		// Closures are currently implemented as objects
		$function = array( $function, '' );
	} else {
		$function = (array) $function;
	}

	if (is_object($function[0]) ) {
		// Object Class Calling
		if ( function_exists('spl_object_hash') ) {
			return spl_object_hash($function[0]) . $function[1];
		} else {
			$obj_idx = get_class($function[0]).$function[1];
			if ( !isset($function[0]->a_filter_id) ) {
				if ( false === $priority )
					return false;
				$obj_idx .= isset($a_filter[$tag][$priority]) ? count((array)$a_filter[$tag][$priority]) : $filter_id_count;
				$function[0]->a_filter_id = $filter_id_count;
				++$filter_id_count;
			} else {
				$obj_idx .= $function[0]->a_filter_id;
			}

			return $obj_idx;
		}
	} elseif ( is_string( $function[0] ) ) {
		// Static Calling
		return $function[0] . '::' . $function[1];
	}
}
