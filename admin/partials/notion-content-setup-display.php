<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://patrickchang.com
 * @since      1.0.0
 *
 * @package    Notion_Content
 * @subpackage Notion_Content/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
<form method="post" action="options.php">
<?php
settings_fields( 'notion_content_plugin' );
do_settings_sections( 'notion_content_plugin' );
?>

<?php settings_errors(); ?>
<h1>Notion Content Plugin Setup</h1>
<br><br>
<table>
<tr>
        <td nowrap><strong>Notion API Key: </strong></td>
        <td> <input type="password" name="notion_api_key" value="<?php echo esc_attr( get_option('notion_api_key')) ?>" size="75"> </td>
</tr>
<tr>
        <td colspan="2"> <hr> </td>
</tr>
<tr>
        <td nowrap><strong>Notion Content Database: </strong></td>
        <td> <input type="text" name="notion_content_database" value="<?php echo esc_attr( get_option('notion_content_database')) ?>" size="100"> </td>
</tr>
<tr>
        <td colspan="2"> <hr> </td>
</tr>
<tr>
        <td nowrap><strong>*Refresh Interval: </strong></td>
        <td> 
                <select name="notion_refresh_interval">
                        <option value="5" <?php selected(get_option('notion_refresh_interval'), "5"); ?>>5 mintues</option>
                        <option value="10" <?php selected(get_option('notion_refresh_interval'), "10"); ?>>10 mintues</option>
                        <option value="15" <?php selected(get_option('notion_refresh_interval'), "15"); ?>>15 mintues</option>
                        <option value="30" <?php selected(get_option('notion_refresh_interval'), "15"); ?>>30 mintues</option>
                        <option value="60" <?php selected(get_option('notion_refresh_interval'), "60"); ?>>1 hour</option>
                        <option value="None" <?php selected(get_option('notion_refresh_interval'), "None"); ?>>No automatic refresh</option>
                </select>
        </td>
</tr>
<tr>
        <td> </td>
        <td> * The shorter the interval, the more calls to the Notion API.  This is good if content is constantly being updated on Notion but can slow down the performance of your page.  If you choose "No automatic refresh", you will have to manually refresh the content in the "Page Content" page.  
        </td>
</tr>
</table>
<?php submit_button(); ?>
</form>
</div>