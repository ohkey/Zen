<?php
	header("content-type:text/html;charset=utf-8");
	$oper = @$_POST['oper'];
	
	$conn = mysql_connect("localhost", "root", 'root');
	if(!$conn) {
		die('failed...');
	}
	mysql_select_db("test", $conn);
	mysql_query("set names utf8");
	
	 function no_browser_cache(){
		header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' );
	}
	function replace(&$row, $title, $content) {
		
		$row = str_replace("%title%", $title, $row);
		$row = str_replace("%content%", $content, $row);
		
		return $row;
	}
	if ($oper == "add") {
		
		$tilte = @$_POST['title'];
		$content = @$_POST['content'];
		
		$sql = "insert into news(title, content) values('$tilte', '$content')";
		
		if (mysql_query($sql, $conn)) {
			
			//获取刚刚插入数据的ID号
			$id = mysql_insert_id();
			$html_filename = "news_id" . $id . ".html";
			
			$fp_tmp = fopen('template.html', "r");
			$fp_html_file = fopen($html_filename, "w");
			no_browser_cache();
			while (!feof($fp_tmp)) {
				$row = fgets($fp_tmp);
				$new_row = replace($row, $tilte, $content);
				fputs($fp_html_file, $new_row);
			}
			fclose($fp_tmp);
			fclose($fp_html_file);
			
			echo "success...." . "<a href='news_list.php'>return list </a>";
		}
		
		mysql_close($conn);
	}else if ($oper == 'update') {
		
		$tilte = @$_POST['title'];
		$content = @$_POST['content'];
		$id = $_POST['id'];
		
		$sql = "update news set title = '$tilte' , content = '$content' where id = $id ";
		if (mysql_query($sql, $conn)) {
			
			$html_filename = "news_id" . $id . ".html";
			unlink($html_filename);
			
			$fp_tmp = fopen('template.html', "r");
			$fp_html_file = fopen($html_filename, "w");
			//$my_header = "<head><meta http-equiv='Cache-Control'</head>"	
			while (!feof($fp_tmp)) {
				$row = fgets($fp_tmp);
				$new_row = replace($row, $tilte, $content);
				fputs($fp_html_file, $new_row);
			}
			fclose($fp_tmp);
			fclose($fp_html_file);
				
			echo "update success...." . "<a href='news_list.php'>return list </a>";
		} else {
			echo "update error...";
		}
	}
	
	
?>








