<?php

/*
// Alkaline
// Copyright (c) 2010-2011 by Budin Ltd. All rights reserved.
// Do not redistribute this code without written permission from Budin Ltd.
// http://www.alkalineapp.com/
*/

require_once('./../config.php');
require_once(PATH . CLASSES . 'alkaline.php');

$alkaline = new Alkaline;
$orbit = new Orbit;
$user = new User;

$user->perm(true, 'posts');

// GET POST
if(!empty($_GET['id'])){
	$post_id = $alkaline->findID($_GET['id']);
}

if(!empty($_GET['act'])){
	$post_act = @$_GET['act'];
	if($post_act == 'search'){
		Find::clearMemory();

		$post_ids = new Find('posts');
		$post_ids->find();
		$post_ids->saveMemory();
		
		header('Location: ' . LOCATION . BASE . ADMIN . 'posts' . URL_ACT . 'results' . URL_RW);
		exit();
	}
}

// SAVE CHANGES
if(!empty($_POST['post_id'])){
	if(!$post_id = $alkaline->findID($_POST['post_id'])){
		header('Location: ' . LOCATION . BASE . ADMIN . 'posts' . URL_CAP);
		exit();
	}
	
	$posts = new Post($post_id);
	
	if(!empty($_POST['post_delete']) and ($_POST['post_delete'] == 'delete')){
		$alkaline->deleteRow('posts', $post_id);
	}
	else{
		$post_title = trim($_POST['post_title']);
		
		if(!empty($_POST['post_title_url'])){
			$post_title_url = $alkaline->makeURL($_POST['post_title_url']);
		}
		else{
			$post_title_url = $alkaline->makeURL($post_title);
		}
		
		$post_text_raw = $_POST['post_text_raw'];
		$post_text = $post_text_raw;
		
		$post_excerpt_raw = $_POST['post_excerpt_raw'];
		$post_excerpt = $post_excerpt_raw;
		
		// Configuration: post_markup
		if(!empty($_POST['post_markup'])){
			$post_markup_ext = $_POST['post_markup'];
			$post_text = $orbit->hook('markup_' . $post_markup_ext, $post_text_raw, $post_text);
			$post_title = $orbit->hook('markup_title_' . $post_markup_ext, $post_title, $post_title);
			$post_excerpt = $orbit->hook('markup_' . $post_markup_ext, $post_excerpt_raw, $post_excerpt);
		}
		elseif($alkaline->returnConf('web_markup')){
			$post_markup_ext = $alkaline->returnConf('web_markup_ext');
			$post_text = $orbit->hook('markup_' . $post_markup_ext, $post_text_raw, $post_text);
			$post_title = $orbit->hook('markup_title_' . $post_markup_ext, $post_title, $post_title);
			$post_excerpt = $orbit->hook('markup_' . $post_markup_ext, $post_excerpt_raw, $post_excerpt);
		}
		else{
			$post_markup_ext = '';
			$post_text = $this->nl2br($post_text_raw);
			$post_excerpt = $this->nl2br($post_excerpt_raw);
		}
		
		// Comment disabling
		if(@$_POST['post_comment_disabled'] == 'disabled'){
			$post_comment_disabled = 1;
		}
		else{
			$post_comment_disabled = 0;
		}
		
		$post_images = implode(', ', $alkaline->findIDRef($post_text));
		
		$post_words = $alkaline->countWords($_POST['post_text_raw'], 0);
		
		$fields = array('post_title' => $alkaline->makeUnicode($post_title),
			'post_title_url' => $post_title_url,
			'post_text_raw' => $alkaline->makeUnicode($post_text_raw),
			'post_markup' => $post_markup_ext,
			'post_images' => $post_images,
			'post_text' => $alkaline->makeUnicode($post_text),
			'post_published' => @$_POST['post_published'],
			'post_category' => $alkaline->makeUnicode(@$_POST['post_category']),
			'post_comment_disabled' => $post_comment_disabled,
			'post_words' => $post_words);
		
		$posts->attachUser($user);
		$posts->updateFields($fields);
	}
	
	unset($post_id);
}
else{
	$alkaline->deleteEmptyRow('posts', array('post_title', 'post_text_raw'));
}

define('TAB', 'posts');

// CREATE POST
if(!empty($post_act) and ($post_act == 'add')){
	$fields = array('user_id' => $user->user['user_id']);
	$post_id = $alkaline->addRow($fields, 'posts');
}

// GET POSTS TO VIEW OR PAGE TO EDIT
if(empty($post_id)){
	$post_ids = new Find('posts');
	$post_ids->page(null, 50);
	if(isset($post_act) and ($post_act == 'results')){ $post_ids->memory(); }
	$post_ids->find();
	
	$posts = new Post($post_ids);
	$posts->hook();
	
	define('TITLE', 'Alkaline Posts');
	require_once(PATH . ADMIN . 'includes/header.php');

	?>

	<div class="span-24 last">
		<div class="actions">
			<a href="<?php echo BASE . ADMIN . 'posts' . URL_ACT . 'add' . URL_RW; ?>"><button>Write post</button></a>
			<a href="<?php echo BASE . ADMIN . 'upload' . URL_CAP; ?>"><button>Upload post</button></a>
			<?php if($badges['posts'] > 0){ ?>
			<a href="<?php echo BASE . ADMIN . 'shoebox' . URL_CAP; ?>">
				<button>Process posts (<?php echo $badges['posts']; ?>)</button>
			</a>
			<?php } ?>
		</div>
	
		<h1><img src="<?php echo BASE . ADMIN; ?>images/icons/posts.png" alt="" /> Posts (<?php echo number_format($posts->post_count); ?>)</h1>
	
		<form action="<?php echo BASE . ADMIN; ?>posts<?php echo URL_ACT; ?>search<?php echo URL_RW; ?>" method="post">
			<p style="margin-bottom: 0;">
				<input type="search" name="q" style="width: 30em; margin-left: 0;" results="10" /> <input type="submit" value="Search" />
			</p>

			<p>
				<span class="switch">&#9656;</span> <a href="#" class="show" style="line-height: 2.5em;">Show options and presets</a>
			</p>
		
			<div class="reveal span-24 last">
				<div class="span-15 append-1">
					<table>
						<tr>
							<td class="right middle"><label for="published">Publication status:</label></td>
							<td class="quiet">
								<select id="published" name="published">
									<option value="">All</option>
									<option value="published">Published</option>
									<option value="unpublished">Unpublished</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="right middle"><label>Date created:</label></td>
							<td class="quiet">
								between <input type="text" class="date" name="created_begin" style="width: 10em;" />
								and <input type="text" class="date" name="created_end" style="width: 10em;" />
							</td>
						</tr>
						<tr>
							<td class="right middle"><label>Date modified:</label></td>
							<td class="quiet">
								between <input type="text" class="date" name="modified_begin" style="width: 10em;" />
								and <input type="text" class="date" name="modified_end" style="width: 10em;" />
							</td>
						</tr>
						<tr>
							<td class="right middle"><label>Sort results by:</label></td>
							<td>
								<select name="sort">
									<option value="created" selected="selected">Date created</option>
									<option value="modified">Date modified</option>
									<option value="published">Date published</option>
									<option value="title">Title</option>
									<option value="views">Views</option>
								</select>
								<select name="sort_direction">
									<option value="DESC">Descending</option>
									<option value="ASC">Ascending</option>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div class="span-8 last">
					<h3>Presets</h3>
					
					<ul>
						<li><a href="<?php echo BASE . ADMIN . 'posts' . URL_ACT . 'displayed' . URL_RW; ?>">Displayed posts</a></li>
						<li><a href="<?php echo BASE . ADMIN . 'posts' . URL_ACT . 'updated' . URL_RW; ?>">Recently updated posts</a></li>
						<li><a href="<?php echo BASE . ADMIN . 'posts' . URL_ACT . 'views' . URL_RW; ?>">Most viewed posts</a></li>
					</ul>
					
					<ul>
						<li><a href="<?php echo BASE . ADMIN . 'posts' . URL_ACT . 'unpublished' . URL_RW; ?>">Unpublished posts</a></li>
					</ul>
				</div>
			</div>
		</form>
	
		<table>
			<tr>
				<th>Title</th>
				<th></th>
				<th class="center">Views</th>
				<th class="center">Words</th>
				<th>Created</th>
				<th>Last modified</th>
			</tr>
			<?php

			foreach($posts->posts as $post){
				echo '<tr class="ro">';
					echo '<td><a href="' . BASE . ADMIN . 'posts' . URL_ID . $post['post_id'] . URL_RW . '" title="' . $alkaline->fitStringByWord($post['post_text'], 150) . '" class="tip"><strong class="large">' . $post['post_title'] . '</strong></a><br />
						<a href="' . BASE . 'post' . URL_ID . $post['post_id'] . '-' . $post['post_title_url'] . URL_RW . '" class="nu quiet">' . $post['post_title_url'] . '</td>';
					echo '<td class="center"><button class="tip" title=\'<select><option value="publish">Publish</option><option value="view_images">View images</option></select> <input type="Submit" value="Do" />\'></button></td>';
					echo '<td class="center">' . number_format($post['post_views']) . '</td>';
					echo '<td class="center">' . number_format($post['post_words']) . '</td>';
					echo '<td>' . $alkaline->formatTime($post['post_created']) . '</td>';
					echo '<td>' . ucfirst($alkaline->formatRelTime($post['post_modified'])) . '</td>';
				echo '</tr>';
			}

			?>
		</table>
	
		<?php
		if($post_ids->page_count > 1){
			?>
			<p>
				<?php
				if(!empty($post_ids->page_previous)){
					for($i = 1; $i <= $post_ids->page_previous; ++$i){
						$page_uri = 'page_' . $i . '_uri';
						echo '<a href="' . $post_ids->$page_uri  .'" class="page_no">' . number_format($i) . '</a>';
					}
				}
				?>
				<span class="page_no">Page <?php echo $post_ids->page; ?> of <?php echo $post_ids->page_count; ?></span>
				<?php
				if(!empty($post_ids->page_next)){
					for($i = $post_ids->page_next; $i <= $post_ids->page_count; ++$i){
						$page_uri = 'page_' . $i . '_uri';
						echo '<a href="' . $post_ids->$page_uri  .'" class="page_no">' . number_format($i) . '</a>';
					}
				}
				?>
			</p>
			<?php
		}
		?>
	</div>

	<?php
	
	require_once(PATH . ADMIN . 'includes/footer.php');
}
else{
	$posts = new Post($post_id);
	$posts->getVersions();
	$posts->formatTime();
	
	$post = $posts->posts[0];
	$post = $alkaline->makeHTMLSafe($post);
	
	$now = time();
	$launch_action = '';
	
	if(!empty($post['post_published'])){
		$published = strtotime($post['post_published']);
		if($published <= $now){
			$launch_action = '<a href="' . BASE . 'post' . URL_ID . $post['post_id'] . URL_RW . '"><button>Launch post</button></a>';
		}
	}
	
	if(!empty($post_act) and ($post_act == 'add')){
		if($user->returnPref('post_pub') === true){
			$post['post_published'] = 'Now';
			$post['post_published_format'] = 'Now';
		}
	}
	
	if(!empty($post['post_title'])){	
		define('TITLE', 'Alkaline Post: &#8220;' . $post['post_title']  . '&#8221;');
	}
	else{
		define('TITLE', 'Alkaline Post');
	}
	require_once(PATH . ADMIN . 'includes/header.php');

	?>
	
	<div class="actions">
		<a href="<?php echo BASE . ADMIN . 'search' . URL_ACT . 'posts' . URL_AID .  $post['post_id'] . URL_RW; ?>"><button>View images</button></a>
		<?php echo $launch_action; ?>
	</div>
	
	<?php
	
	if(empty($post['post_title'])){
		echo '<h1><img src="' . BASE . ADMIN . 'images/icons/posts.png" alt="" /> New Post</h1>';
	}
	else{
		echo '<h1><img src="' . BASE . ADMIN . 'images/icons/posts.png" alt="" /> Post: ' . $post['post_title'] . '</h1>';
	}
	
	?>

	<form id="post" action="<?php echo BASE . ADMIN . 'posts' . URL_CAP; ?>" method="post">
		<div class="span-24 last">
			<div class="span-15 append-1">
				<input type="text" id="post_title" name="post_title" placeholder="Title" <?php if(empty($post['post_title'])){ echo 'autofocus="autofocus"'; }; ?> value="<?php echo @$post['post_title']; ?>" class="title notempty" />
				<textarea id="post_text_raw" name="post_text_raw" placeholder="Text" style="height: 300px;"  class="<?php if($user->returnPref('text_code')){ echo $user->returnPref('text_code_class'); } ?>"><?php echo @$post['post_text_raw']; ?></textarea>
			</div>
			<div class="span-8 last">
				<p>
					<label for="post_title_url">Custom URL:</label><br />
					<input type="text" id="post_title_url" name="post_title_url" value="<?php echo @$post['post_title_url']; ?>" style="width: 300px;" /><br />
						<span class="quiet"><?php echo 'post' . URL_ID . $post['post_id']; ?>-<span id="post_title_url_link"></span></span>
				</p>
			
				<p>
					<label for="post_published">Publish date:</label><br />
					<input type="text" id="post_published" name="post_published" placeholder="Draft" value="<?php echo @$post['post_published_format']; ?>" />
				</p>
				
				<p>
					<label for="post_category">Category:</label><br />
					<input type="text" id="post_category" name="post_category" class="post_category" value="<?php echo @$post['post_category']; ?>" />
				</p>
			
				<hr />
				<table>
					<?php if($alkaline->returnConf('comm_enabled')){ ?>
					<tr>
						<td><input type="checkbox" id="post_comment_disabled" name="post_comment_disabled" value="disabled" <?php if($post['post_comment_disabled'] == 1){ echo 'checked="checked"'; } ?> /></td>
						<td>
							<strong><label for="post_comment_disabled">Disable comments on this post.</label></strong>
						</td>
					</tr>
					<?php } ?>
					<tr>
						<td><input type="checkbox" id="post_delete" name="post_delete" value="delete" /></td>
						<td><label for="post_delete">Delete this post.</label><br />This action cannot be undone.</td>
					</tr>
				</table>
			</div>
		</div>
		
		<?php if(count($posts->versions) > 0){ ?>
		<p class="slim">
			<span class="switch">&#9656;</span> <a href="#" class="show">Compare to previous version</a>
		</p>
		<div class="reveal">
			<p>
				<label for="version_id">Show differences from:</label>
				<select id="version_id">
				<?php
				foreach($posts->versions as $version){
					echo '<option value="' . $version['version_id'] . '">' . ucfirst($alkaline->formatRelTime($version['version_created'])) . ' (#' . $version['version_id'] . ')</option>';
				}
				?>
				</select>
				<button id="compare">Compare</button>
			</p>
			<p id="comparison">
				
			</p>
		</div>
		<?php } ?>
		
		<p>
			<span class="switch">&#9656;</span> <a href="#" class="show">Show recent images</a> <span class="quiet">(click to add at cursor position)</span>
		</p>
		<div class="reveal image_click">
			<?php
	
			$image_ids = new Find('images');
			$image_ids->sort('image_uploaded', 'DESC');
			$image_ids->post(1, 100);
			$image_ids->find();
	
			$images = new Image($image_ids);
			$images->getSizes();
	
			if($alkaline->returnConf('post_size_label')){
				$label = 'image_src_' . $alkaline->returnConf('post_size_label');
			}
			else{
				$label = 'image_src_admin';
			}
	
			if($alkaline->returnConf('post_div_wrap')){
				echo '<div class="none wrap_class">' . $alkaline->returnConf('post_div_wrap_class') . '</div>';
			}
	
			foreach($images->images as $image){
				$image['image_title'] = $alkaline->makeHTMLSafe($image['image_title']);
				echo '<a href="' . $image[$label] . '"><img src="' . $image['image_src_square'] .'" alt="' . $image['image_title']  . '" class="frame" id="image-' . $image['image_id'] . '" /></a>';
				echo '<div class="none uri_rel image-' . $image['image_id'] . '">' . $image['image_uri_rel'] . '</div>';
			}

			?>
		</div>
		<p>
			<input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>" />
			<input type="hidden" id="post_markup" name="post_markup" value="<?php echo $post['post_markup']; ?>" />
			<input type="submit" value="Save changes" />
			or <a href="<?php echo $alkaline->back(); ?>">cancel</a></p>
	</form>

	<?php

	require_once(PATH . ADMIN . 'includes/footer.php');	
}

?>