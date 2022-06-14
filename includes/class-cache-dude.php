<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 * 
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       https://github.com/liaisontw/cache-dude
 * @since      1.0.0
 *
 * @package    cache-dude
 * @subpackage cache-dude/include
 * @author     Liaison Chang
 */

class Cache_Dude {
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $name    The string used to uniquely identify this plugin.
	 */
	protected $name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	/**
	 * The utility of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $utility    The instance of Poll_Dude_Utility Class of the plugin.
	 */
	public $utility;
	public $admin;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CACHE_DUDE_VERSION' ) ) {
			$this->version = CACHE_DUDE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->name = 'cache-dude';
		$this->load_dependencies();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cache_Dude_Admin. Defines all hooks for the admin area.
	 * - Cache_Dude_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cache-dude-utility.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cache-dude-admin.php';

		//$this->utility = new cache_dude\Cache_Dude_Utility();		
		add_action( 'plugins_loaded',  	array($this, 'set_textdomain') );
	}

	### Function: Set Text Domain
	public function set_textdomain() {
		load_plugin_textdomain( 'cache-dude' );
	}
	
		
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$this->admin = new Cache_Dude_Admin( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->name;
	}

	public function get_admin() {
		return $this->admin;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
