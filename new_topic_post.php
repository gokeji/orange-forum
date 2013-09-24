<?php
define('FORUM_ROOT', './');
require FORUM_ROOT . 'common.php';


$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';
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
			 <img src="images/orange-forum.jpg" alt="the-orange" />
  		</div>
  		<div id="stats" class="content">
  			<div id="declaration">
	  			<?php if($type=='post'): ?> New Post <?php elseif(1): ?> New Topic <?php endif; ?> 
  			</div>
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
				<?php if($type=='post'): ?> >>> <?php endif; ?> 
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
  		<div class="clearer"></div>
		<div id="container" class="content">
					<form action="post.php?type=<?php echo $type; ?>&forumId=<?php echo $forum_id; ?>&topicId=<?php echo $topic_id; ?>" method="post">
						<p>Author: <input type="text" name="name" value="<?php if ($user){echo $user_profile['name'];}?>"/></p>
						<?php if($type=='topic'): ?><p>Subject: <input type="text" name="subject" /></p><?php endif; ?>
						<p>Message:</p>
						<textarea name="message" cols=60 rows=15></textarea>
						<input type="hidden" name="csrfToken" value="<?php echo $_SESSION['csrfToken'];?>" />
						<p><input type="submit" name="submit" value="Submit"/></p>
						
					</form>
		</div>
		<div class="float"><?php require FORUM_ROOT . 'facebook-login.php'; ?></div>
	</body>
</html>