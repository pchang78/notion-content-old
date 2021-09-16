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
        <td><strong>Notion API Key: </strong></td>
        <td> <input type="password" name="notion_api_key" value="<?php echo esc_attr( get_option('notion_api_key')) ?>" size="75"> </td>
</tr>


<tr>
        <td><strong>Notion Content Database: </strong></td>
        <td> <input type="text" name="notion_content_database" value="<?php echo esc_attr( get_option('notion_content_database')) ?>" size="100"> </td>
</tr>


</table>




<?php submit_button(); ?>

</form>


</div>

