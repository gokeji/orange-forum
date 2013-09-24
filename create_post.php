<?php
			

    			
					if(!isset($topic_id)){
						$topic_id = isset($_GET['topicId']) ? (int)htmlspecialchars($_GET['topicId']) : 0;
					}
					$query = $dbh->prepare("INSERT INTO `sql_posts`( `author`, `author_id`, `posted_time`, `contents`, `topic_id`) 
					VALUES (:name,:aid, :mydate, :message,:tid)");
					
					$query->bindParam(':message', $message, PDO::PARAM_STR, 12);
					$query->bindParam(':name', $name, PDO::PARAM_STR, 12);
					$query->bindParam(':aid', $user_profile['id'], PDO::PARAM_INT);
					$query->bindParam(':mydate', date('y-m-d h:i:s'), PDO::PARAM_STR, 12);
					$query->bindParam(':tid', $topic_id, PDO::PARAM_INT);
					$query->execute();
					/* TODO: Update Topic*/		
					$query = $dbh->prepare("UPDATE `sql_forums` SET `num_posts`=(SELECT COUNT('post_id') FROM `sql_posts` WHERE `topic_id` IN (SELECT `topic_id` FROM `sql_topics` WHERE `forum_id`=:fid)) WHERE `forum_id`=:fid");
					$query->bindParam(':fid', $forum_id, PDO::PARAM_INT);
					$query->execute();
					$query = $dbh->prepare("UPDATE `sql_topics` SET `num_replies`=(SELECT COUNT('post_id')-1 FROM `sql_posts` WHERE `topic_id`=:tid), `last_poster`=:name, `last_post_time`=(SELECT MAX(`posted_time`) FROM `sql_posts` WHERE `topic_id`=:tid)  WHERE `topic_id`=:tid");
					$query->bindParam(':name', $name, PDO::PARAM_STR, 12);
					$query->bindParam(':tid', $topic_id, PDO::PARAM_INT);
					$query->execute();
					
					//$direct_to = file_get_contents("./view-topic.php?page=0&forumId=". $forum_id . "&topicId=" . $topic_id, true);
					//echo $direct_to;
					//require ("view-topic.php?page=0&forumId=". $forum_id . "&topicId=" . $topic_id);
					header("Refresh: 0; url=view-topic.php?page=0&forumId=". $forum_id . "&topicId=" . $topic_id);
					exit('Post Successful! Redirecting...');
				
			
					
?>