

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <link rel="STYLESHEET" type="text/css" href="style.css" />
    <title>Biblioteka</title>
</head>
<body>
    <div class="header" style="font-size: 130px;">Biblioteka</div>
    <div id="container" style="margin-top: 30px;">
	<form method="POST" action="">		
	    <input type="text" name="login" placeholder="login" onfocus="this.placeholder=''" onblur="this.placeholder='login'">
	    <input type="password" name="passwd" placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'">
	    <input type="submit" name="submit" value="Zaloguj się">
	</form>
    </div>
        
<?php
if (isset($_POST['submit'])) {
    $dsn = 'pgsql:host=localhost;dbname=DB_PROJ_KUSAK';
    $user = $_POST['login'];
    $password = $_POST['passwd'];
    
    try {
        $pdo = new PDO($dsn, $user, $password);
        echo 'Connected succesfully';
        header('Location: Xmain.php');
    }catch (PDOException $e) {
        echo '<br>Connection failed: ' . $e->getMessage().'<br>';
        echo 'Halo! Do nauki! <br> Eeeeeeeeeeeeee';
    }
}
?>
    
</body>
</html>
