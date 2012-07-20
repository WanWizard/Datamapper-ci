<?php	if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Datamapper Configuration
 *
 * Global configuration settings that apply to all DataMapped models.
 */

/**
 * model_prefix:
 *
 * this allows you to prefix your model classes to avoid class name collisions
 */
$config['model_prefix'] = 'model_';

/**
 * table:
 *
 * this is NOT a global configuration value, it is listed here for completeness
 */
$config['table_name'] = null;

/**
 * table_prefix:
 *
 * prefix all tables with this prefix. Codeigniters prefix is NOT used !
 */
$config['table_prefix'] = null;

/**
 * database:
 *
 * name of the database definition to use, or null for the platform default
 */
$config['database'] = null;




// ************************
// OLD STUFF !
// ************************
/*

$config['prefix'] = '';
$config['join_prefix'] = '';
$config['error_prefix'] = '<p>';
$config['error_suffix'] = '</p>';
$config['created_field'] = 'created';
$config['updated_field'] = 'updated';
$config['local_time'] = FALSE;
$config['unix_timestamp'] = FALSE;
$config['timestamp_format'] = '';
$config['lang_file_format'] = 'model_${model}';
$config['field_label_lang_format'] = '${model}_${field}';
$config['auto_transaction'] = FALSE;
$config['auto_populate_has_many'] = FALSE;
$config['auto_populate_has_one'] = FALSE;
$config['all_array_uses_ids'] = FALSE;
// set to FALSE to use the same DB instance across the board (breaks subqueries)
// Set to any acceptable parameters to $CI->database() to override the default.
$config['db_params'] = '';
// Uncomment to enable the production cache
// $config['production_cache'] = 'datamapper/cache';
$config['extensions_path'] = 'datamapper';
$config['extensions'] = array();

*/

/* End of file datamapper.php */
/* Location: ./application/config/datamapper.php */
