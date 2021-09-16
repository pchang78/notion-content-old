<?php

/**
 * Fired during plugin activation
 *
 * @link       https://patrickchang.com
 * @since      1.0.0
 *
 * @package    Notion_Content
 * @subpackage Notion_Content/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Notion_Content
 * @subpackage Notion_Content/includes
 * @author     Patrick Chang <patrick@patrickchang.com>
 */
class Notion_Content_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$table_name = $wpdb->prefix . "notion_content";
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				page_id varchar(105) DEFAULT '' NOT NULL,
				page_name varchar(255) DEFAULT '' NOT NULL,
				page_content text NOT NULL,
				`status` varchar(20) DEFAULT 'Active' NOT NULL,

				PRIMARY KEY  (id)
				) $charset_collate;";


		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}
