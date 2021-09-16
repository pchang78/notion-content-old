<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://patrickchang.com
 * @since      1.0.0
 *
 * @package    Notion_Content
 * @subpackage Notion_Content/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Notion_Content
 * @subpackage Notion_Content/public
 * @author     Patrick Chang <patrick@patrickchang.com>
 */
class Notion_Content_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Notion_Content_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Notion_Content_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/notion-content-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Notion_Content_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Notion_Content_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/notion-content-public.js', array( 'jquery' ), $this->version, false );

	}


	public function register_shortcodes() {
		add_shortcode( 'notion_content', array( $this, 'display_notion_content') );
		//add_shortcode( 'anothershortcode', array( $this, 'another_shortcode_function') );
	}


	// [bartag foo="foo-value"]
	function display_notion_content( $atts ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "notion_content";
		$a = shortcode_atts( array( 'id' => '0',), $atts );
		$id = $a["id"];
		$my_content = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = $id" );
		$time= $my_content->time;

		$cut_off = date("Y-m-d H:i:s", strtotime("-5 minutes"));
		if($time < $cut_off) {
			$page_id = $my_content->page_id;
			$plugin_admin = new Notion_Content_Admin( $this->plugin_name, $this->version );
			$text = $plugin_admin->refresh_notion_content_pub($page_id);

		}
		else {
			$text = $my_content->page_content;
		}
		return $text;
	}


}
