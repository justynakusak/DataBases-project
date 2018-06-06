<?php
    require 'connection.php';
    $pdo = connection();
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <link rel="STYLESHEET" type="text/css" href="style.css" />
    <title>Biblioteka</title>
</head>
<body>

    <div class="header">Biblioteka</div>
    <div class="nav">			
	<ol>
		<li><a href="Xmain.php">Katalog</a></li>
		<li><a href="Xcategories.php">Kategorie</a></li>
		<li><a href="Xadd.php">Dodaj wpis</a></li>
		<li><a href="Xlogin.php">Twoje konto</a></li>
	</ol>
    </div>

    <div class="article">
	<h1 align="center">Zaloguj się lub zarejestruj:</h1>      
        
    <div id="container" style="margin-top: 30px;">
	<form method="POST" action="">		
	    <input type="text" name="login" placeholder="login" onfocus="this.placeholder=''" onblur="this.placeholder='login'">
	    <input type="password" name="password" placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'">
	    <input type="submit" name="submit" value="Zaloguj się">
	</form>
    </div>
        
<?php   

    if (isset($_POST['submit'])) {
		$error = array();
        
		$login = trim($_POST['login']);
		$password = trim($_POST['password']);
        
		if (!isset($login) || empty($login)) 
			array_push($error, 'Podaj login!');
		else if (!isset($password) || empty($password))
			array_push($error, 'Podaj hasło!');
		$checked = true;
        
		if (count($error) == 0) {
			$query = 'SELECT nazwa, haslo FROM uzytkownicy WHERE nazwa = :login AND haslo = :password';
			if ($sth = $pdo->prepare($query)) { 
				$sth->bindValue(':login', $login, PDO::PARAM_STR);
				$sth->bindValue(':password', $password, PDO::PARAM_STR);
                if (!$sth->execute()) 
                    print_r($pdo->errorInfo());
                else {
                    if($sth->rowCount() > 0)
                       $logged = true;
                }
            }
            if(!isset($logged)) {   
                $query = 'INSERT INTO uzytkownicy (nazwa, haslo) VALUES (:login, :password)';
                if ($sth = $pdo->prepare($query)) {
                    $sth->bindValue(':login', $login, PDO::PARAM_STR);
                    $sth->bindValue(':password', $password, PDO::PARAM_STR);
                    if (!$sth->execute()) 
                        print_r($pdo->errorInfo());
                    else
                        $logged = true;  
                }
            }     
            header('Location: Xuser.php?login=' . $login);
        }
        else {
            if ($checked) {
                foreach ($error as $row)
                    echo $row;
            }
        }
    }    
?>

    </div>

    <div class="footer">
    </div>


</body>
</html>

