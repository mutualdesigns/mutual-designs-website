<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<script>
	function showDiv(){
		var obj = document.getElementById('content');
		obj.style.display = 'block';
	}
</script>

</head>

<body onLoad="setTimeout('showDiv()', 3000);" style="background-color:#000;">

<div id='content' style="display: none;border:1px #000 solid;width:300px;height:100px;background-color:#FFF;position:fixed;bottom:50px;left:500px;">
Take this survey as soon as you're finished!
</div>

</body>
</html>