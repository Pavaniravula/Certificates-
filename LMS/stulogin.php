<?php
session_cache_limiter('private');
session_start();
header("Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f2f2f2;
			background-image: url("https://media.istockphoto.com/id/1367756031/vector/light-blue-watercolor-background-illustration.jpg?s=612x612&w=0&k=20&c=qiJl7j-2terwHd-1YJxiFIo1VJx6l6IsmoqlgeypQ7c=");
		}
		form {
			background-color: rgb(47, 93, 131);
			padding: 20px;
			border-radius: 5px;
			box-shadow: 0px 0px 10px #163d5e;
			width: 400px;
			margin: 0 auto;
			margin-top: 50px;
		}
		input[type=text], input[type=password] {
			padding: 10px;
			border-radius: 5px;
			border: none;
            background-color: #ebeff3;
			margin-bottom: 10px;
			width: 100%;
		}
		input[type=submit] {
			background-color: #4CAF50;
			color: white;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
		}
		input[type=submit]:hover {
			background-color: #45a049;
		}
		header {
			background-image: url("https://i0.wp.com/codemyui.com/wp-content/uploads/2019/06/Shooting-Star-Background-in-Pure-CSS-1.gif?fit=880%2C440&ssl=1&resize=350%2C250");
		background-color: #12197e;
        color: #e43a9d;
        padding: 10px;
		text-shadow: 2px 2px 4px #e2ec4d;
      }
.previous-button {       
                position: down;
              background-color:green ;
              color: white;
              padding: 7px 7px;
              border:none;
              border-radius: 5px;
              cursor: pointer;
              top: 6px;
              left:6px;
              text-size:10px
            }
	</style>
	<script>
        if (window.history && window.history.pushState) {
            window.history.pushState('', null, './stulogin.html');
            window.addEventListener('popstate', function () {
                window.history.pushState('', null, './stulogin.html');
            });
        }
    </script>
</head>
<body>
	<header>
		<center>
		<h1> EDUSTAR </h1>
		</center>
	  </header>
	<form method="post" action="log.php">
		<h2>Student Login</h2>
		<label>Email:</label>
		<input type="text" name="email" id="email" required>
		<label>Password:</label>
		<input type="password" name="pas" id="pas" required>
		<input type="submit" value="Login"><br><br>
<a class="previous-button" href="index.html">&laquo; Previous</a>
	</form>
</body>
</html>
