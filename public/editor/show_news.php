<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
</head>
<body>
<?php
	header("content-type=text/html;charset=utf-8");
	$id = @$_GET['id'];
	
	$html_filename = "news_id" . $id .".html";
	
	if (file_exists($html_filename)&&filemtime($html_filename)+30 >time()) {
		header("location:" . $html_filename);
		exit();
	}
	$conn = mysql_connect("localhost", 'root', 'root');
	if (!$conn) {
		die("failed...");
	}
	mysql_select_db("test", $conn);
	mysql_query("set names utf8");
	$sql = "select * from news where id = $id" ;
	$res= mysql_query($sql);
	ob_start();
	if ($row = mysql_fetch_assoc($res)) {
		echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
		echo "<table border='1px' bordercolor='green' cellspacing='0' width='400px' height='200px'>";
		echo "<tr><td>新闻详情</td></tr>";
		echo "<tr><td>{$row['title']}</td></tr>";
		echo "<tr><td>{$row['content']}</td></tr>";
		echo "</table>";
	} else {
		echo "no record....";
	}
	$html_content = ob_get_contents();
	
	file_put_contents($html_filename, $html_content);
	mysql_free_result($res);
	mysql_close($conn);
?>
</body>
</html>
















