<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://github.com/liaisontw/poll-dude
 * @since      1.0.0
 * @package    poll-dude
 * @subpackage poll-dude/includes
 * @author     Liaison Chang
 */
class Cache_Dude_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'admin_menu',                	array($this, 'admin_menu') );
		add_action( 'admin_enqueue_scripts',     	array($this, 'admin_scripts') );
	}

	public function admin_scripts($hook_suffix){
		$this->enqueue_scripts();
		$this->enqueue_styles();
		/*
		$admin_pages = array($this->plugin_name.'/cache-dude.php', $this->plugin_name.'/view/page-poll-dude-add-form.php', $this->plugin_name.'/view/page-poll-dude-control-panel.php', $this->plugin_name.'/view/page-cache-dude-options.php');
		if(in_array($hook_suffix, $admin_pages, true)) {			
			$this->enqueue_scripts();
			$this->enqueue_styles();
		}
		*/
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
        wp_enqueue_script('jquery-ui-tabs');
	}

	public function admin_menu() {
		add_options_page(
        	/* $page_title  */ __( 'Cache Dude', 'cache-dude' ), 
			/* $menu_title  */ __( 'Cache Dude', 'cache-dude' ), 
			/* $capability  */ 'manage_options', 
			/* $menu_slug   */ plugin_dir_path( dirname( __FILE__ ) ) . '/view/page-cache-dude-options.php'
        );
	}

}
