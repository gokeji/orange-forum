<?php
define('FORUM_ROOT', './');
require FORUM_ROOT . 'common.php';


$posts_per_page = 25;
$at_end = FALSE;
?>

<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<link rel="stylesheet" type="text/css" href="main.css" />
    	<script type="text/javascript" src="simple.js"/>
		</script>
  	</head>
  	<body>
  		<div id="bg"></div>
  		<div id="header">
			 <img src="images/orange-forum.png" alt="the-orange" />
  		</div>
  		<div id="breadcrumb" class="content">
  			<div id="declaration">
	  			Current Location: 
  			</div>
  			<div id="navi0">
  				<a href="index.php"> Main</a>  >>>  
			</div>
			<div id="navi1">
				<?php 
					$page = isset($_GET['page']) ? (int)htmlspecialchars($_GET['page']) : 0;
					$starting_post = $page * $posts_per_page;
					$forum_id = isset($_GET['forumId']) ? (int)htmlspecialchars($_GET['forumId']) : 0;
					$topic_id = isset($_GET['topicId']) ? (int)htmlspecialchars($_GET['topicId']) : 0;
				?>
				<a href="view-forum.php?forumId=<?php echo $forum_id;?>">
				<?php
					$query = $dbh->prepare('SELECT * FROM  `sql_forums` WHERE `forum_id`=:fid');
					$query->execute(array(':fid' => $forum_id));
					$forum = $query->fetch();
					$forum = $forum[1];
					echo $forum;
				?>
				</a>
				 >>>
			</div>
			<div id="navi2">
				<?php
					$query = $dbh->prepare('SELECT * FROM  `sql_topics` WHERE `topic_id`=:tid');
					$query->execute(array(':tid' => $topic_id));
					$topic = $query->fetch();
					echo $topic[1];
				?>
			</div>
  		</div>
		<div id="container">
			<table id="main"  class="content">
							<!-- <tr class="forum" id="table_header">
								<th>
									<div class="author">Forum Subject</div>
									<div class="profile pic">Author</div>
								</th>
								<th><div class="num_replies">Replies</div></th>
								<th><div class="last_poster">Last Poster</div></th>
								<th><div class="last_post_time">Last Post Time</div></th>
							</tr> -->
				<?php
					$query = $dbh->prepare('SELECT * FROM `sql_posts` WHERE `topic_id`=:tid ORDER BY `posted_time` DESC LIMIT :page,:ppp');
					$query->bindParam(':tid', $topic_id, PDO::PARAM_INT);
					$query->bindParam(':ppp', $posts_per_page, PDO::PARAM_INT);
					$query->bindParam(':page', $starting_post, PDO::PARAM_STR, 12);					
					$query->execute();
					$topics = $query->fetchAll();
					//print_r($topics);
					
					 for($k = 0; $k < count($topics); $k++) { ?>
							<tr class="forum no_select">
								<td><div class="poster">
									<div class="author"><?php echo $topics[$k][1];?></div>
									<img src=
										<?php if ($user): ?>"https://graph.facebook.com/<?php echo $topics[$k][2]; ?>/picture" 
										<?php else: ?>"images/unkown.jpg" <?php endif ?>
										>
									<div class="post_time"><?php echo $topics[$k][3];?></div>
								</div></td>
								<td class="post_content"><div class="post_content"><?php echo $topics[$k][4];?></div></td>
							</tr>
					<?php } ?>
					<?php require 'error_check.php'; ?>
					<?php
						//Determine if end of pages is reached
						$query = $dbh->prepare('SELECT COUNT(`post_id`) FROM `sql_posts` WHERE `topic_id`=:tid');
						$query->bindParam(':tid', $topic_id, PDO::PARAM_INT);			
						$query->execute();
						$post_count = $query->fetch();
						$post_count = $post_count[0];
						
						if($post_count <= ($page +1) * $posts_per_page){
							$at_end = TRUE;
						}
					?>
					
			</table>
			<div class="content navigation">
				<a href="view-topic.php?forumId=<?php echo $forum_id; ?>&topicId=<?php echo $topic_id;?>&page=<?php echo $page-1; ?>" <?php if($page<=0): ?> class="disabled" disabled="true" onclick="return false" <?php endif; ?>> Prev <<< </a>
				 Current Page : <?php echo $page+1 ?>
				<a href="view-topic.php?forumId=<?php echo $forum_id; ?>&topicId=<?php echo $topic_id;?>&page=<?php echo $page+1; ?>" <?php if($at_end): ?> class="disabled" disabled="true" onclick="return false" <?php endif; ?>>  >>> Next</a>
			</div>
			<div class="content button"><div id="new_topic" onclick="nav('new_topic_post.php?type=post&forumId=<?php echo $forum_id; ?>&topicId=<?php echo $topic_id; ?>')">Create New Post</div></div>
  			<?php require FORUM_ROOT . 'facebook-login.php'; ?>
		</div>
	</body>
</html>
