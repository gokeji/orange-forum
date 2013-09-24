<?php

	//check forum_id is valid
	if(isset($forum_id)){
		$query = $dbh->prepare('SELECT COUNT(`forum_id`) FROM  `sql_forums` WHERE 1');
		$query->execute();
		$forum_count = $query->fetch();
		$forum_count = $forum_count[0];
		
		if($forum_id>$forum_count){
		
		echo "<tr class='forum no_select center'><td colspan=5><div>The forum you request does not exist.</div></td></tr>";
		} /*else {
		
			//check topic_id is valid
			if(isset($topic_id)){
				$query = $dbh->prepare('SELECT `topic_id` FROM  `sql_topics` WHERE `forum_id`=:fid');
				$query->bindParam(':fid', $forum_id, PDO::PARAM_INT);		
				$query->execute();
				$topic_count = $query->fetchAll();
				print_r($topic_count);
				if(!in_array($topic_id, $topic_count)){
		
				echo "<tr class='forum no_select center'><td colspan=2><div>The Topic you request does not exist.</div></td></tr>";
				}
		
			}
		}*/
	}
	
	
	
	
?>