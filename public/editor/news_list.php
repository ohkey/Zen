<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>新闻列表页</title>
</head>
<body>
<?php
	header("content-type=text/html;charset=utf-8");
	$conn = mysql_connect("localhost", "root", 'root');
	if(!$conn) {
		die('failed...');
	}
	mysql_select_db("test", $conn);
	mysql_query("set names utf8");
	$sql = "select * from news";
	
	$res= mysql_query($sql);
	echo "<h1>新闻列表页</h1>";
	echo "<a href='add_news.html'>添加新闻</a><hr />";
	echo "<table>";
	echo "<tr><td>id</td><td>title</td><td>详情</td></tr>";
	while($row = mysql_fetch_assoc($res)) {
		echo "<tr><td>". $row['id'] ."</td><td>" . $row['title']. "</td><td><a href='news_id{$row['id']}.html'>detail</a></td><td><a href='update_newsui.php?id={$row['id']}'>edit</a></tr>";
	}
	echo "</table>";
	mysql_free_result($res);
	mysql_close($conn);
	
?>
</body>
</html>


















