<?php
    require 'connection.php';
    $pdo = connection();

	if (isset($_POST['submit'])) {
		$error = array();
		
		$title = trim($_POST['title']);
		$author = trim($_POST['author']);
		$category = trim($_POST['category']);
		$year = trim($_POST['year']);
		
		if (!isset($title) || empty($title)) 
			array_push($error, 'Podaj tytuł!');
		else if (!isset($author) || empty($author))
			array_push($error, 'Podaj autora!');
		else if (!isset($category) || empty($category))
			array_push($error, 'Wybierz kategorie!');	
		else if (!isset($year) || empty($year))
			array_push($error, 'Podaj rok wydania!');
		
		$checked = true;
		if (count($error) == 0) {
			
			$query = 'INSERT INTO ksiazki (tytul, autor, kategoria, rok_wydania) VALUES (:title, :author, :category, :year)';
			if ($sth = $pdo->prepare($query)) {
				$sth->bindValue(':title', $title, PDO::PARAM_STR);
				$sth->bindValue(':author', $author, PDO::PARAM_STR);
				$sth->bindValue(':category', $category, PDO::PARAM_STR);
				$sth->bindValue(':year', $year, PDO::PARAM_INT);
				if (!$sth->execute()) {
					push_array($error, 'Błąd serwera');
                    print_r($pdo->errorInfo());
				}
			}
		}
	}
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
    
	<?php
	    $sth = $pdo->query('SELECT nazwa FROM kategorie order by id;');
	    $result = $sth->fetchAll();
	?>
    
    <div class="article">
	<h1 align="center">Dodaj książkę do katalogu:</h1>    
        
    <form method="POST" action="" >	       
	    Wpisz tytuł książki: <input type="text" name="title" placeholder="tytuł" onfocus="this.placeholder=''" onblur="this.placeholder='tytuł'" style="margin-left: 150px;"></br>
	    Wpisz imię i nazwisko autora książki: <input type="text" name="author" placeholder="autor" onfocus="this.placeholder=''" onblur="this.placeholder='autor'" style="margin-left: 5px;"></br>
        Wybierz kategorię: 
            <select name="category" style="margin-left: 157px;">
            <?php foreach ($result as $row): ?>
            <option value="<?php echo $row["nazwa"]; ?>"><?php echo $row["nazwa"]; ?></option>
            <?php endforeach; ?>
            </select></br>
	    Wpisz rok wydania książki: <input type="number" name="year" placeholder="rok wydania" onfocus="this.placeholder=''" onblur="this.placeholder='rok wydania'" style="margin-left: 87px;"></br>
	    <input type="submit" name="submit" value="Dodaj" style="margin-left: 315px;"></br>
	</form>
    
    <?php if ($checked): ?>
        <?php if (count($error) == 0): ?>
            <p align="center">Udało się dodać książkę do katalogu :)</p>
        <?php else: ?>
            <p>Nie udało się dodać książki do katalogu :(</p>
            <?php foreach ($error as $row): ?>
            <?php echo $row; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>

    </div>

    <div class="footer">
    </div>

</body>
</html>
