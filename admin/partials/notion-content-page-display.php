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

<div class="wrap">


<?php if($my_content) : ?>


<small><a href="?page=notion-content&action=refresh_list">Refresh List</a></small>


<table class="data">
<tr>
	<th>Page Name</th>
	<th>Shortcode</th>
	<th>Action</th>
</tr>
<?php foreach($my_content AS $content_row) : ?>
<tr>
	<td><?php echo $content_row->page_name; ?></td>
	<td>
	[notion_content id=<?php echo $content_row->id; ?>]
	</td>
	<td>
	<small>
		<a href="admin.php?page=notion-content&action=view_content&page_id=<?php echo $content_row->page_id; ?>">View Content</a> |
		<a href="admin.php?page=notion-content&action=refresh_content&page_id=<?php echo $content_row->page_id; ?>">Refresh Content</a>
	</small>
	</td>
</tr>
<?php endforeach; ?>
</table>

<?php else : ?>

	<a href="?page=notion-content&action=refresh_list">List Content</a>

<?php endif ?>

</div>

