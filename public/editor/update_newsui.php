<?php
	header("content-type:text/html; charset=utf-8");
	$id = @$_GET['id'];
	
	$conn = mysql_connect("localhost", "root", 'root');
	if(!$conn) {
		die('failed...');
	}
	mysql_select_db("test", $conn);
	mysql_query("set names utf8");
	
	$sql = "select * from news where id = $id ";
	$res = mysql_query($sql);
	
	if ($row = mysql_fetch_assoc($res)) {
		echo "<form action='newsAction.php' method='post'>";
		echo "<input type='text' name='title' value='{$row['title']}'/><br />";
		echo "<input type='text' name='id' value= '{$row['id']}' readonly /> <br/>";
		echo '<input type="hidden" name="oper" value="update" />';
		echo "<textarea name='content' >{$row['content']}</textarea><br />";
		echo "<input type='submit' value='submit' />";
		echo "</form>";
		
	} else {
		echo " id error...";
	}
	
	
?>