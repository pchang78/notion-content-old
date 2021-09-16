<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://patrickchang.com
 * @since      1.0.0
 *
 * @package    Notion_Content
 * @subpackage Notion_Content/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Notion_Content
 * @subpackage Notion_Content/admin
 * @author     Patrick Chang <patrick@patrickchang.com>
 */
class Notion_Content_Admin {

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

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/notion-content-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/notion-content-admin.js', array( 'jquery' ), $this->version, false );

	}




	public function add_plugin_admin_menu() {
		// add_options_page( 'Email Press Settings', 'Email Press Release', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));
		add_menu_page('Notion Content Settings', 'Notion Content', 'edit_pages', 'notion-content', array($this,'display_page_content_setup'));
                add_submenu_page('notion-content', 'Notion Page Content', 'Page Content', 'edit_pages', 'notion-content', array($this, 'display_page_content_setup'));
                add_submenu_page('notion-content', 'Notion Content Setup', 'Setup', 'edit_pages', 'notion-content-setup', array($this, 'display_plugin_setup_page'));
		register_setting("notion_content_plugin", "notion_api_key");
		register_setting("notion_content_plugin", "notion_content_database");
        }

        public function display_plugin_setup_page() {
                global $post;
		include_once("partials/notion-content-setup-display.php");
	}

	private function refresh_notion_page_list() {
		global $wpdb;
		$table_name = $wpdb->prefix . "notion_content";
		settings_fields( 'notion_content_plugin' );
		$api = esc_attr( get_option('notion_api_key'));
		$url = esc_attr( get_option('notion_content_database'));



		$dID = array_shift(explode("?", str_replace("https://www.notion.so/", "", $url)));



		$database_id = substr($dID, 0, 8)."-".substr($dID, 8, 4)."-".substr($dID, 12, 4)."-".substr($dID, 16, 4)."-".substr($dID, 20, 12);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://api.notion.com/v1/databases/$database_id/query");
		$headers = [ 'Authorization: Bearer ' . $api, 'Content-Type: application/json', 'Notion-Version: 2021-08-16'];
		$postData ='
		{
			"sorts": [{ "property": "Name", "direction": "ascending" }]
		}';


		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec ($ch);
		$arrResult = json_decode($result, true);


		$wpdb->update($table_name, array('status' => 'inactive'), array('status' => 'Active'));
		foreach($arrResult["results"] AS $row) {
			$page_id = $row["id"];
			$page_name = $row["properties"]["Name"]["title"][0]["plain_text"];
			if($wpdb->get_row("SELECT * FROM $table_name WHERE page_id='$page_id'")) {
				$wpdb->update($table_name, array('page_name' => $page_name, 'status' => 'Active'), array('page_id' => $page_id));
			}
			else {
				// Insert into db
				$time = date("Y-m-d H:i:s");
				$wpdb->insert($table_name, array('time'=> $time, 'page_id' => $page_id, 'page_name' => $page_name));
			}
		}
	}



	private function refresh_notion_content($page_id, $return_content = false) {
		global $wpdb;
		$table_name = $wpdb->prefix . "notion_content";
		if (!function_exists('settings_fields')) {
			$my_content = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "options WHERE option_name = 'notion_api_key'");
			$api = $my_content->option_value;
		}
		else {
			settings_fields( 'notion_content_plugin' );
			$api = esc_attr( get_option('notion_api_key'));
		}
		$ch = curl_init();
		$page_content = "";
		$pID = str_replace("-", "", $page_id);
		curl_setopt($ch, CURLOPT_URL,"https://api.notion.com/v1/blocks/$pID/children?page_size=100");
		$headers = [ 'Authorization: Bearer ' . $api, 'Content-Type: application/json', 'Notion-Version: 2021-08-16'];
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec ($ch);
		$arrResult = json_decode($result, true);
		$arrAnnotations = array( "bold" => "strong", "italic" => "i", "strikethrough" => "del", "underline" => "u", "code" => "code");
		$bulleted_list_item = false;
		$numbered_list_item = false;
		foreach($arrResult["results"] AS $block_row) {
			$block_content = "";
			$block_type = $block_row["type"];
			foreach($block_row[$block_type]["text"] AS $block_text) {
				reset($arrAnnotations);
				$open_tag = "";
				$close_tag = "";
				foreach($arrAnnotations AS $ntag => $html_tag) {
					if($block_text["annotations"][$ntag]) {
						$open_tag .= "<$html_tag>";
						$close_tag = "</$html_tag>" . $close_tag;
					}
				}
				$block_content .= $open_tag . $block_text["text"]["content"] . $close_tag;
			}
			$pre = "";
			if($block_type != "bulleted_list_item" && $bulleted_list_item) {
				$pre = "</ul>\n";
				$bulleted_list_item = false;
			}
			if($block_type != "numbered_list_item" && $numbered_list_item) {
				$pre = "</ol>\n";
				$numbered_list_item = false;
			}
			switch($block_type) {
				case "heading_1":
					$block_content = "$pre<h1>$block_content</h1>\n";
					break;
				case "heading_2":
					$block_content = "$pre<h2>$block_content</h2>\n";
					break;
				case "heading_3":
					$block_content = "$pre<h3>$block_content</h3>\n";
					break;
				case "paragraph":
					$block_content = "$pre<p>$block_content</p>\n";
					break;
				case "bulleted_list_item":
					if(!$bulleted_list_item) {
						$bulleted_list_item = true;
						$block_content = "<ul>\n\t<li>$block_content</li>\n";
						
					}
					else {
						$block_content = "\t<li>$block_content</li>\n";
					}
					break;
				case "numbered_list_item":
					if(!$numbered_list_item) {
						$numbered_list_item = true;
						$block_content = "<ol>\n\t<li>$block_content</li>\n";
						
					}
					else {
						$block_content = "\t<li>$block_content</li>\n";
					}
					break;
			}
			$page_content .= $block_content;
		}
		if($bulleted_list_item) {
			$page_content .= "</ul>\n";
		}
		if($numbered_list_item) {
			$page_content .= "</ol>\n";
		}
		$time = date("Y-m-d H:i:s");
		$wpdb->update($table_name, array('page_content' => $page_content, 'time' => $time), array('page_id' => $page_id));
		if($return_content) {
			return $page_content;
		}
	}

	public function refresh_notion_content_pub($page_id) {
		$page_content = $this->refresh_notion_content($page_id, true);
		return $page_content;
	}

	public function display_page_content_setup() {
		global $post;
		global $wpdb;
		$table_name = $wpdb->prefix . "notion_content";
		switch($_GET["action"]) {
			case "refresh_content":
				$this->refresh_notion_content($_GET["page_id"]);
				$url = "?page=notion-content";
				echo "<script> window.location.href='$url'; </script>";
				exit;
				break;
			case "refresh_list":
				$this->refresh_notion_page_list();
				$url = "?page=notion-content";
				echo "<script> window.location.href='$url'; </script>";
				exit;
				break;
			default:
				$content_list = "";
				$my_content = $wpdb->get_results( "SELECT * FROM $table_name WHERE `status`='Active'" );
				include_once("partials/notion-content-page-display.php");
		}
	}
}



