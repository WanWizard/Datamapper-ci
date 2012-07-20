<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Datamapper ORM - Vendor package loader library
 *
 * @license     MIT License
 * @package     DataMapper ORM
 * @category    DataMapper ORM
 * @author      Harro "WanWizard" Verton
 * @link        http://datamapper.wanwizard.eu
 * @version     2.0.0
 */

/**
 * Datamapper main class. This class will setup the PSR environment needed
 * to use the Datamapper package.
 *
 * NOTE: This class is final, extend \Datamapper\Model for your CI models!
 */
final class Datamapper
{
	/**
	 * @var	\Composer\Autoload\ClassLoader	storage for the composer autoloader
	 */
	protected $loader = null;

	/**
	 * @var	array	storage for custom model paths
	 */
	protected $paths = array();

	/**
	 * Datamapper library constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		// flag to make sure we initialize only once
		static $initialized = false;

		// setup the composer autoloader
		if ( ! $initialized	)
		{
			// environment version check
			if ( version_compare(PHP_VERSION, '5.3.0') < 0 )
			{
				show_error('Fatal error: Datamapper requires PHP 5.3.0 or above!');
			}

			// load the composer autoloader class
			require __DIR__.'/../vendor/composer/ClassLoader.php';

			// create an autoload instance
			$this->loader = new \Composer\Autoload\ClassLoader();

			// register classes with namespaces
			$this->loader->add('Datamapper', __DIR__.'/../vendor/datamapper/classes');
			$this->loader->add('Cabinet\DBAL', __DIR__.'/../vendor/cabinet/classes');

			// activate the autoloader
			$this->loader->register();

			// initialize Datamapper
			try
			{
				\Datamapper\Platform\Platform::initialize();
			}
			catch (Exception $e)
			{
				show_error('Fatal error: Datamapper initialisation failed ('.$e->getMessage().')');
			}

			// setup the datamapper CI model autoloader class
			spl_autoload_register(array($this, 'load_model'), true);

			// mark the autoloader as initialized
			$initialized = true;
		}
	}

    /**
     * Loads the given CI model class.
     *
     * @param	string	$class	the name of the class
     *
     * @return	bool	true, if loaded, false if it could not be found
     */
    public function load_model($class)
    {
        if ( $file = $this->find_model($class) )
        {
            include $file;
            return true;
        }

        return false;
    }

    /**
     * Finds the path to the file where the class is defined.
     *
     * @param	string	$class	the name of the model class to be loaded
     *
     * @return	string|bool	the fully qualified path to the class file, if found
     */
    public function find_model($class)
    {
		static $CI = null;

		// get the CI instance
		is_null($CI) and $CI =& get_instance();

		// Don't attempt to autoload CI_ , EE_, or custom prefixed classes
		if ( in_array(substr($class, 0, 3), array('CI_', 'EE_')) or strpos($class, $CI->config->item('subclass_prefix')) === 0 )
		{
			return false;
		}

		// Prepare class
		$class = strtolower($class);

		// Prepare the model search paths
		$paths = array();
		if ( method_exists($CI->load, 'get_package_paths') )
		{
			// use CI 2.0 loader's model paths
			$paths = $CI->load->get_package_paths(false);
		}

		foreach ( array_merge(array(APPPATH),$paths, $this->paths) as $path )
		{
			// Prepare file
			$file = $path . 'models/' . $class . EXT;

			// Check if file exists, require_once if it does
			if ( file_exists($file) )
			{
				return $file;
			}
		}

		// if the model wasn't found, do a recursive search of model paths for the class
		foreach( $paths as $path )
		{
			if ( $file = $this->recursive_find_model($class, $path . 'models') )
			{
				return $file;
			}
		}

		// not found, we give up
		return false;
	}

	/**
	 * try to find the model class by recursing through the model folders
	 *
	 * @param	string	$class	name of class to look for
	 * @param	string	$path	current path to search
	 *
	 * @return string|bool	full path to the class, or false if not found
	 */
	protected function recursive_find_model($class, $path)
	{
		if ( is_dir($path) )
		{
			$handle = opendir($path);
			if ( $handle )
			{
				while ( false !== ($dir = readdir($handle)) )
				{
					// if dir does not contain a dot
					if ( strpos($dir, '.') === false )
					{
						// prepare recursive path
						$recursive_path = $path . '/' . $dir;

						// prepare file
						$file = $recursive_path . '/' . $class . '.php';

						// check if file exists, and return it if it does
						if ( file_exists($file) )
						{
							return $file;
						}

						// not found, recurse
						if ( is_dir($recursive_path) )
						{
							// Do a recursive search of the path for the class
							return $this->recursive_find_model($class, $recursive_path);
						}
					}
				}

				closedir($handle);
			}
		}

		// class not found
		return false;
	}

}
