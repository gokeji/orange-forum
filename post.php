<?php
define('FORUM_ROOT', './');
require FORUM_ROOT . 'common.php';
		
			//print($_POST['csrfToken']);
			//print($_SESSION['csrfToken']);
			if(isset($_POST['submit']))
			{
				//check the tokens
    			if($_SESSION['csrfToken'] == $_POST['csrfToken'])
    			{
    				
				$type = isset($_GET['type']) ? htmlspecialchars(trim($_GET['type'])) : 0;
				$message = isset($_POST['message']) ? htmlspecialchars($_POST['message']): '';
				$forum_id = isset($_GET['forumId']) ? (int)htmlspecialchars($_GET['forumId']) : 0;
				$name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])): '';
					if (empty($name))
					{
						exit('Please fill in your name');
					}
					if (empty($message))
					{
						exit('Please fill in a message');
					}
					
				if($type == 'topic'){
					
					$subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])): '';
					if (empty($subject))
					{
						exit('Please fill in a subject');
						
					}
					
					$query = $dbh->prepare("INSERT INTO `sql_topics`( `subject`, `author`, `num_replies`, `forum_id`) 
					VALUES (:subject,:name,0,:fid)");
					$query->bindParam(':subject', $subject, PDO::PARAM_STR, 12);
					$query->bindParam(':name', $name, PDO::PARAM_STR, 12);
					$query->bindParam(':fid', $forum_id, PDO::PARAM_INT);
					$query->execute();
					
					
					/* update forum topic counts */
					$query = $dbh->prepare("UPDATE `sql_forums` SET `num_topics`=(SELECT COUNT('topic_id') FROM `sql_topics` WHERE `forum_id`=:fid) WHERE `forum_id`=:fid");
					$query->bindParam(':fid', $forum_id, PDO::PARAM_INT);
					$query->execute();	
					
					$query = $dbh->prepare("SELECT MAX(`topic_id`) FROM `sql_topics` WHERE `forum_id`=:fid");
					$query->bindParam(':fid', $forum_id, PDO::PARAM_INT);
					$query->execute();	
					$topic = $query->fetch();
					$topic_id = $topic[0];
					
					require 'create_post.php';
							
					header("Refresh: 0; url=view-forum.php?page=0&forumId=" . $forum_id);
					exit('Post Successful! Redirecting...');
				} else {
					require 'create_post.php';
					
				}
			} else {
					echo 'CSRF attack!';
			}
		} else {
			echo 'No submit, CSRF attack!';
		}


?>



