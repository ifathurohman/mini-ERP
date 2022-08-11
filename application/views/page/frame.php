<!DOCTYPE html>
<html>
<head>
	<title>Attachment</title>
	<style type="text/css">
		html, body
		{
		   height: 100%;
		   margin: 0 auto;
		   overflow: hidden;
		   text-align: center;
		}
		.content{
			width: 100%;
			height: 100%;
			margin: auto;
		}
		.content img{
		    width: 100%;
		    height: 100%;
		    object-fit: none;
		}
	</style>
	<?= $style ?>
</head>
<body>
	<div class="content"><?= $frame ?></div>
</body>
</html>