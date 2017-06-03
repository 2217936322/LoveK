<?php
function mysql_clean_admin() {	
	add_options_page('数据优化页面', '数据库优化清理','manage_options', basename(__FILE__), 'mysql_clean_page');
}
function mysql_clean_page(){
?>
<div class="wrap">
	
<?php screen_icon(); ?>
<h2>数据库优化清理（我叫KK最牛逼）</h2>

<?php
function mysql_clean($type){
	global $wpdb;
	switch($type){
		case "revision":
			$wcu_sql = "DELETE FROM $wpdb->posts WHERE post_type = 'revision'";
			$wpdb->query($wcu_sql);
			break;
		case "draft":
			$wcu_sql = "DELETE FROM $wpdb->posts WHERE post_status = 'draft'";
			$wpdb->query($wcu_sql);
			break;
		case "autodraft":
			$wcu_sql = "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'";
			$wpdb->query($wcu_sql);
			break;
		case "moderated":
			$wcu_sql = "DELETE FROM $wpdb->comments WHERE comment_approved = '0'";
			$wpdb->query($wcu_sql);
			break;
		case "spam":
			$wcu_sql = "DELETE FROM $wpdb->comments WHERE comment_approved = 'spam'";
			$wpdb->query($wcu_sql);
			break;
		case "trash":
			$wcu_sql = "DELETE FROM $wpdb->comments WHERE comment_approved = 'trash'";
			$wpdb->query($wcu_sql);
			break;
		case "postmeta":
			$wcu_sql = "DELETE pm FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
			$wpdb->query($wcu_sql);
			break;
		case "commentmeta":
			$wcu_sql = "DELETE FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)";
			$wpdb->query($wcu_sql);
			break;
		case "relationships":
			$wcu_sql = "DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id=1 AND object_id NOT IN (SELECT id FROM $wpdb->posts)";
			$wpdb->query($wcu_sql);
			break;
		case "feed":
			$wcu_sql = "DELETE FROM $wpdb->options WHERE option_name LIKE '_site_transient_browser_%' OR option_name LIKE '_site_transient_timeout_browser_%' OR option_name LIKE '_transient_feed_%' OR option_name LIKE '_transient_timeout_feed_%'";
			$wpdb->query($wcu_sql);
			break;
	}
}

function mysql_clean_count($type){
	global $wpdb;
	switch($type){
		case "revision":
			$wcu_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision'";
			$count = $wpdb->get_var($wcu_sql);
			break;
		case "draft":
			$wcu_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'draft'";
			$count = $wpdb->get_var($wcu_sql);
			break;
		case "autodraft":
			$wcu_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'auto-draft'";
			$count = $wpdb->get_var($wcu_sql);
			break;
		case "moderated":
			$wcu_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '0'";
			$count = $wpdb->get_var($wcu_sql);
			break;
		case "spam":
			$wcu_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'spam'";
			$count = $wpdb->get_var($wcu_sql);
			break;
		case "trash":
			$wcu_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'trash'";
			$count = $wpdb->get_var($wcu_sql);
			break;
		case "postmeta":
			$wcu_sql = "SELECT COUNT(*) FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL";
			$count = $wpdb->get_var($wcu_sql);
			break;
		case "commentmeta":
			$wcu_sql = "SELECT COUNT(*) FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)";
			$count = $wpdb->get_var($wcu_sql);
			break;
		case "relationships":
			$wcu_sql = "SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id=1 AND object_id NOT IN (SELECT id FROM $wpdb->posts)";
			$count = $wpdb->get_var($wcu_sql);
			break;
		case "feed":
			$wcu_sql = "SELECT COUNT(*) FROM $wpdb->options WHERE option_name LIKE '_site_transient_browser_%' OR option_name LIKE '_site_transient_timeout_browser_%' OR option_name LIKE '_transient_feed_%' OR option_name LIKE '_transient_timeout_feed_%'";
			$count = $wpdb->get_var($wcu_sql);
			break;
	}
	return $count;
}

function mysql_clean_optimize(){
	global $wpdb;
	$wcu_sql = 'SHOW TABLE STATUS FROM `'.DB_NAME.'`';
	$result = $wpdb->get_results($wcu_sql);
	foreach($result as $row){
		$wcu_sql = 'OPTIMIZE TABLE '.$row->Name;
		$wpdb->query($wcu_sql);
	}
}

	$wcu_message = '';

	if(isset($_POST['mysql_clean_revision'])){
		mysql_clean('revision');
		$wcu_message = __("All revisions deleted!","MYSQL_Clean_KK");
	}

	if(isset($_POST['mysql_clean_draft'])){
		mysql_clean('draft');
		$wcu_message = __("All drafts deleted!","MYSQL_Clean_KK");
	}

	if(isset($_POST['mysql_clean_autodraft'])){
		mysql_clean('autodraft');
		$wcu_message = __("All autodrafts deleted!","MYSQL_Clean_KK");
	}
	
	if(isset($_POST['mysql_clean_moderated'])){
		mysql_clean('moderated');
		$wcu_message = __("All moderated comments deleted!","MYSQL_Clean_KK");
	}

	if(isset($_POST['mysql_clean_spam'])){
		mysql_clean('spam');
		$wcu_message = __("All spam comments deleted!","MYSQL_Clean_KK");
	}

	if(isset($_POST['mysql_clean_trash'])){
		mysql_clean('trash');
		$wcu_message = __("All trash comments deleted!","MYSQL_Clean_KK");
	}

	if(isset($_POST['mysql_clean_postmeta'])){
		mysql_clean('postmeta');
		$wcu_message = __("All orphan postmeta deleted!","MYSQL_Clean_KK");
	}

	if(isset($_POST['mysql_clean_commentmeta'])){
		mysql_clean('commentmeta');
		$wcu_message = __("All orphan commentmeta deleted!","MYSQL_Clean_KK");
	}

	if(isset($_POST['mysql_clean_relationships'])){
		mysql_clean('relationships');
		$wcu_message = __("All orphan relationships deleted!","MYSQL_Clean_KK");
	}

	if(isset($_POST['mysql_clean_feed'])){
		mysql_clean('feed');
		$wcu_message = __("All dashboard transient feed deleted!","MYSQL_Clean_KK");
	}

	if(isset($_POST['mysql_clean_all'])){
		mysql_clean('revision');
		mysql_clean('draft');
		mysql_clean('autodraft');
		mysql_clean('moderated');
		mysql_clean('spam');
		mysql_clean('trash');
		mysql_clean('postmeta');
		mysql_clean('commentmeta');
		mysql_clean('relationships');
		mysql_clean('feed');
		$wcu_message = __("All redundant data deleted!","MYSQL_Clean_KK");
	}

	if(isset($_POST['mysql_clean_optimize'])){
		mysql_clean_optimize();
		$wcu_message = __("Database Optimized!","MYSQL_Clean_KK");
	}

	if($wcu_message != ''){
		echo '<div id="message" class="updated fade"><p><strong>' . $wcu_message . '</strong></p></div>';
	}
?>

<style>
#inlojv-t1,#inlojv-t2{overflow:hidden;float:left}
#inlojv-t1{margin-right:30px}
</style>
<div id="inlojv-t1">
<table class="widefat" style="width:419px;">
	<thead>
		<tr>
			<th scope="col"><?php _e('Table','MYSQL_Clean_KK'); ?></th>
			<th scope="col"><?php _e('Size','MYSQL_Clean_KK'); ?></th>
		</tr>
	</thead>
	<tbody id="the-list">
	<?php
		global $wpdb;
		$total_size = 0;
		$alternate = " class='alternate'";
		$wcu_sql = 'SHOW TABLE STATUS FROM `'.DB_NAME.'`';
		$result = $wpdb->get_results($wcu_sql);

		foreach($result as $row){

			$table_size = $row->Data_length + $row->Index_length;
			$table_size = $table_size / 1024;
			$table_size = sprintf("%0.3f",$table_size);

			$every_size = $row->Data_length + $row->Index_length;
			$every_size = $every_size / 1024;
			$total_size += $every_size;

			echo "<tr". $alternate .">
					<td class='column-name'>". $row->Name ."</td>
					<td class='column-name'>". $table_size ." KB"."</td>
				</tr>\n";
			$alternate = (empty($alternate)) ? " class='alternate'" : "";
		}
	?>
	</tbody>
	<tfoot>
		<tr>
			<th scope="col"><?php _e('Total','MYSQL_Clean_KK'); ?></th>
			<th scope="col" style="font-family:Tahoma;"><?php echo sprintf("%0.3f",$total_size).' KB'; ?></th>
		</tr>
	</tfoot>
</table>
<p>
<form action="" method="post">
	<input type="hidden" name="mysql_clean_optimize" value="optimize" />
	<input type="submit" class="button-primary" value="<?php _e('Optimize','MYSQL_Clean_KK'); ?>" />
</form>
</p>
<br />
</div>


<div id="inlojv-t2">
<table class="widefat" style="width:419px;">
	<thead>
		<tr>
			<th scope="col"><?php _e('Type','MYSQL_Clean_KK'); ?></th>
			<th scope="col"><?php _e('Count','MYSQL_Clean_KK'); ?></th>
			<th scope="col"><?php _e('Operate','MYSQL_Clean_KK'); ?></th>
		</tr>
	</thead>
	<tbody id="the-list">
		<tr class="alternate">
			<td class="column-name">
				<?php _e('Revision','MYSQL_Clean_KK'); ?>
			</td>
			<td class="column-name">
				<?php echo mysql_clean_count('revision'); ?>
			</td>
			<td class="column-name">
				<form action="" method="post">
					<input type="hidden" name="mysql_clean_revision" value="revision" />
					<input type="submit" class="<?php if(mysql_clean_count('revision')>0){echo 'button-primary';}else{echo 'button';} ?>" value="<?php _e('Delete','MYSQL_Clean_KK'); ?>" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="column-name">
				<?php _e('Draft','MYSQL_Clean_KK'); ?>
			</td>
			<td class="column-name">
				<?php echo mysql_clean_count('draft'); ?>
			</td>
			<td class="column-name">
				<form action="" method="post">
					<input type="hidden" name="mysql_clean_draft" value="draft" />
					<input type="submit" class="<?php if(mysql_clean_count('draft')>0){echo 'button-primary';}else{echo 'button';} ?>" value="<?php _e('Delete','MYSQL_Clean_KK'); ?>" />
				</form>
			</td>
		</tr>
		<tr class="alternate">
			<td class="column-name">
				<?php _e('Auto Draft','MYSQL_Clean_KK'); ?>
			</td>
			<td class="column-name">
				<?php echo mysql_clean_count('autodraft'); ?>
			</td>
			<td class="column-name">
				<form action="" method="post">
					<input type="hidden" name="mysql_clean_autodraft" value="autodraft" />
					<input type="submit" class="<?php if(mysql_clean_count('autodraft')>0){echo 'button-primary';}else{echo 'button';} ?>" value="<?php _e('Delete','MYSQL_Clean_KK'); ?>" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="column-name">
				<?php _e('Moderated Comments','MYSQL_Clean_KK'); ?>
			</td>
			<td class="column-name">
				<?php echo mysql_clean_count('moderated'); ?>
			</td>
			<td class="column-name">
				<form action="" method="post">
					<input type="hidden" name="mysql_clean_moderated" value="moderated" />
					<input type="submit" class="<?php if(mysql_clean_count('moderated')>0){echo 'button-primary';}else{echo 'button';} ?>" value="<?php _e('Delete','MYSQL_Clean_KK'); ?>" />
				</form>
			</td>
		</tr>
		<tr class="alternate">
			<td class="column-name">
				<?php _e('Spam Comments','MYSQL_Clean_KK'); ?>
			</td>
			<td class="column-name">
				<?php echo mysql_clean_count('spam'); ?>
			</td>
			<td class="column-name">
				<form action="" method="post">
					<input type="hidden" name="mysql_clean_spam" value="spam" />
					<input type="submit" class="<?php if(mysql_clean_count('spam')>0){echo 'button-primary';}else{echo 'button';} ?>" value="<?php _e('Delete','MYSQL_Clean_KK'); ?>" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="column-name">
				<?php _e('Trash Comments','MYSQL_Clean_KK'); ?>
			</td>
			<td class="column-name">
				<?php echo mysql_clean_count('trash'); ?>
			</td>
			<td class="column-name">
				<form action="" method="post">
					<input type="hidden" name="mysql_clean_trash" value="trash" />
					<input type="submit" class="<?php if(mysql_clean_count('trash')>0){echo 'button-primary';}else{echo 'button';} ?>" value="<?php _e('Delete','MYSQL_Clean_KK'); ?>" />
				</form>
			</td>
		</tr>
		<tr class="alternate">
			<td class="column-name">
				<?php _e('Orphan Postmeta','MYSQL_Clean_KK'); ?>
			</td>
			<td class="column-name">
				<?php echo mysql_clean_count('postmeta'); ?>
			</td>
			<td class="column-name">
				<form action="" method="post">
					<input type="hidden" name="mysql_clean_postmeta" value="postmeta" />
					<input type="submit" class="<?php if(mysql_clean_count('postmeta')>0){echo 'button-primary';}else{echo 'button';} ?>" value="<?php _e('Delete','MYSQL_Clean_KK'); ?>" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="column-name">
				<?php _e('Orphan Commentmeta','MYSQL_Clean_KK'); ?>
			</td>
			<td class="column-name">
				<?php echo mysql_clean_count('commentmeta'); ?>
			</td>
			<td class="column-name">
				<form action="" method="post">
					<input type="hidden" name="mysql_clean_commentmeta" value="commentmeta" />
					<input type="submit" class="<?php if(mysql_clean_count('commentmeta')>0){echo 'button-primary';}else{echo 'button';} ?>" value="<?php _e('Delete','MYSQL_Clean_KK'); ?>" />
				</form>
			</td>
		</tr>
		<tr class="alternate">
			<td class="column-name">
				<?php _e('Orphan Relationships','MYSQL_Clean_KK'); ?>
			</td>
			<td class="column-name">
				<?php echo mysql_clean_count('relationships'); ?>
			</td>
			<td class="column-name">
				<form action="" method="post">
					<input type="hidden" name="mysql_clean_relationships" value="relationships" />
					<input type="submit" class="<?php if(mysql_clean_count('relationships')>0){echo 'button-primary';}else{echo 'button';} ?>" value="<?php _e('Delete','MYSQL_Clean_KK'); ?>" />
				</form>
			</td>
		</tr>
		<tr>
			<td class="column-name">
				<?php _e('Dashboard Transient Feed','MYSQL_Clean_KK'); ?>
			</td>
			<td class="column-name">
				<?php echo mysql_clean_count('feed'); ?>
			</td>
			<td class="column-name">
				<form action="" method="post">
					<input type="hidden" name="mysql_clean_feed" value="feed" />
					<input type="submit" class="<?php if(mysql_clean_count('feed')>0){echo 'button-primary';}else{echo 'button';} ?>" value="<?php _e('Delete','MYSQL_Clean_KK'); ?>" />
				</form>
			</td>
		</tr>
	</tbody>
</table>

<p>
<form action="" method="post">
	<input type="hidden" name="mysql_clean_all" value="all" />
	<input type="submit" class="button-primary" value="<?php _e('Delete All','MYSQL_Clean_KK'); ?>" />
</form>
</p>
<br />
</div>


</div>
<?php 
}
add_action('admin_menu', 'mysql_clean_admin');
?>