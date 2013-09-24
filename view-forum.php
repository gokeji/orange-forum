<?php
define('FORUM_ROOT', './');
require FORUM_ROOT . 'common.php';

$topics_per_page = 20;
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
					$page = $page;
					$forum_id = isset($_GET['forumId']) ? (int)htmlspecialchars($_GET['forumId']) : 0;
					$query = $dbh->prepare('SELECT * FROM  `sql_forums` WHERE `forum_id`=:fid');
					$query->execute(array(':fid' => $forum_id));
					$forum = $query->fetch();
					$forum = $forum[1];
					echo $forum;
			?>
			</div>
  		</div>
		<div id="container">
			<table id="main"  class="content">
							<tr class="no_select forum">
								<th><div class="subject">Forum Subject</div></th>
								<th><div class="author">Author</div></th>
								<th><div class="num_replies">Replies</div></th>
								<th><div class="last_poster">Last Poster</div></th>
								<th><div class="last_post_time">Last Post Time</div></th>
							</tr>
				<?php
					$starting_topic = $page * $topics_per_page;
					$query = $dbh->prepare('SELECT * FROM `sql_topics` WHERE `forum_id`=:fid ORDER BY `last_post_time` DESC LIMIT :page,:tpp');
					$query->bindParam(':fid', $forum_id, PDO::PARAM_INT);
					$query->bindParam(':page', $starting_topic, PDO::PARAM_INT);	
					$query->bindParam(':tpp', $topics_per_page, PDO::PARAM_INT);				
					$query->execute();
					$topics = $query->fetchAll();
					//print_r($topics);
					
					 for($k = 0; $k < count($topics); $k++) { ?>
							<tr class="forum" onclick="nav('view-topic.php?forumId=<?php echo $forum_id; ?>&topicId=<?php echo $topics[$k][0];?>&page=0')">
								<td><div class="subject"><?php echo $topics[$k][1];?></div></td>
								<td><div class="author"><?php echo $topics[$k][2];?></div></td>
								<td><div class="num_replies"><?php echo $topics[$k][3];?></div></td>
								<td><div class="last_poster"><?php echo $topics[$k][4];?></div></td>
								<td><div class="last_post_time"><?php echo $topics[$k][5];?></div></td>
							</tr>
					<?php } ?>
					<?php require 'error_check.php'; ?>
					<?php
						//Determine if end of pages is reached
						$query = $dbh->prepare('SELECT COUNT(`topic_id`) FROM `sql_topics` WHERE `forum_id`=:fid');
						$query->bindParam(':fid', $forum_id, PDO::PARAM_INT);			
						$query->execute();
						$topic_count = $query->fetch();
						$topic_count = $topic_count[0];
						
						if($topic_count <= ($page +1) * $topics_per_page){
							$at_end = TRUE;
						}
					?>
					
					
			</table>
			<div class="content navigation">
				<a href="view-forum.php?forumId=<?php echo $forum_id; ?>&page=<?php echo $page-1; ?>" <?php if($page<=0): ?> class="disabled" disabled="true" onclick="return false" <?php endif; ?>> Prev <<< </a>
				 Current Page : <?php echo $page+1 ?>
				<a href="view-forum.php?forumId=<?php echo $forum_id; ?>&page=<?php echo $page+1; ?>" <?php if($at_end): ?> class="disabled" disabled="true" onclick="return false" <?php endif; ?>>  >>> Next</a>
			</div>
			<div class="content button"><div id="new_topic" onclick="nav('new_topic_post.php?type=topic&forumId=<?php echo $forum_id; ?>')">Create New Topic</div></div>
  			<?php require FORUM_ROOT . 'facebook-login.php'; ?>
		</div>
	</body>
</html>