<?php
define('FORUM_ROOT', './');
require FORUM_ROOT . 'common.php';
?>

<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<link rel="stylesheet" type="text/css" href="main.css" />
    	<script type="text/javascript" src="simple.js"></script>
  	</head>
  	<body>
  		<div id="bg"></div>
  		<div id="header">
			 <img src="images/orange-forum.png" alt="the-orange" />
  		</div>
  		<div id="stats" class="content">
  			<div id="declaration">
	  			FORUM STATS:
  			</div>
  			<div id="topics">
  			Topics: 
			<?php $query = $dbh->prepare('SELECT SUM(`num_topics`) FROM  `sql_forums`');
					$query->execute();
					$topics = $query->fetch();
					$topics = $topics[0];
					echo $topics;
			?>
			</div>
			<div id="posts">
  			Posts: 
			<?php $query = $dbh->prepare('SELECT SUM(`num_posts`) FROM  `sql_forums`');
					$query->execute();
					$posts = $query->fetch();
					$posts = $posts[0];
					echo $posts;
			?>
			</div>
  		</div>
		<div id="container">
			<table id="main" class="content">
				<?php
					$query = $dbh->prepare('SELECT * FROM  `sql_categories` WHERE 1 ORDER BY `display_order` DESC');
					$query->execute();
					$categories = $query->fetchAll();
					//print_r($result);
					
					for($i = 0; $i < count($categories); $i++) {
						//print(count($result));
				?>
					<tr class="category"><td colspan=4><?php echo $categories[$i][1]; ?></td></tr>
						<?php
							$query = $dbh->prepare('SELECT * FROM  `sql_forums` WHERE `category_id`=:cid ORDER BY `display_order` DESC');
							$query->execute(array(':cid' => $categories[$i][0]));
							$forums = $query->fetchAll();
						?>
							<?php for($k = 0; $k < count($forums); $k++) { ?>
							<tr class="forum" onclick="nav('view-forum.php?forumId=<?php echo $forums[$k][0]; ?>&page=0')">
								<td><div class="name"><?php echo $forums[$k][1];?></div></td>
								<td><div class="desc"><?php echo $forums[$k][2];?></div></td>
								<td><div class="topics">Topics: <?php echo $forums[$k][3];?></div></td>
								<td><div class="posts">Posts: <?php echo $forums[$k][4];?></div></td>
							</tr>
							<?php }?>
					
				<?php
					}
				?>
			</table>
			<?php require FORUM_ROOT . 'facebook-login.php'; ?>
		</div>
	</body>
</html>

