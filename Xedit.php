<?php
    require 'connection.php';
    $pdo = connection();

    $editId = trim($_GET['id']);

try{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();
	$query = 'SELECT tytul, autor, kategoria, rok_wydania FROM ksiazki WHERE id = :editId for update';
 	if ($sth = $pdo->prepare($query)) {
        $sth->bindValue(':editId', $editId, PDO::PARAM_INT);   
        if ($sth->execute() && $sth->rowCount() > 0) {
            $editBook = $sth->fetch();
            $title = $editBook["tytul"];
            $author =  $editBook["autor"];
            $category = $editBook["kategoria"];
            $year = $editBook["rok_wydania"];
        }
    }

	if (isset($_POST['submit'])) {

        $oldTitle = $title;
        $oldAuthor =  $author;
        $oldCategory = $category;
        $oldYear = $year;
		$title = trim($_POST['title']);
		$author = trim($_POST['author']);
		$category = trim($_POST['category']);
		$year = trim($_POST['year']);
        
        $edited = false;
		if (!isset($title) || empty($title)) $title = $oldTitle;
        else $edited = true;
        if (!isset($author) || empty($author)) $author = $oldAuthor;
        else $edited = true;
        if (!isset($category) || empty($category)) $category = $oldCategory;
        else $edited = true;
        if (!isset($year) || empty($year)) $year = $oldYear;
        else $edited = true;

        if ($edited) {   	
            $query = 'UPDATE ksiazki SET tytul=:title, autor=:author, kategoria=:category, rok_wydania=:year WHERE id=:editId';
            if ($sth = $pdo->prepare($query)) {
                $sth->bindValue(':title', $title, PDO::PARAM_STR);
                $sth->bindValue(':author', $author, PDO::PARAM_STR);
                $sth->bindValue(':category', $category, PDO::PARAM_STR);
                $sth->bindValue(':year', $year, PDO::PARAM_INT);
                $sth->bindValue(':editId', $editId, PDO::PARAM_INT);   
                $sth->execute();
                $pdo->commit();
                $ok = true;
            }
        }
    }
}catch (Exception $e) {
    $pdo->rollBack();
    $ok = false;
    echo "Failed: " . $e->getMessage();
    print_r($pdo->errorInfo());
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
	<h1 align="center">Edytuj wpis w katalogu:</h1>  
        
    <form method="POST" action="" >		
	    Zmień tytuł książki: <input type="text" name="title" placeholder="<?php echo $title; ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo $title; ?>'" style="margin-left: 150px;"></br>
	    Zmień imię i nazwisko autora książki: <input type="text" name="author" placeholder="<?php echo $author; ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo $author; ?>'" style="margin-left: 5px;"></br>
        Zmień kategorię: 
        <select name="category" style="margin-left: 175px;">
            <option selected disabled hidden value="<?php echo $category; ?>"><?php echo $category; ?></option>
            <?php foreach ($result as $row): ?>
            <option value="<?php echo $row["nazwa"]; ?>"><?php echo $row["nazwa"]; ?></option>
            <?php endforeach; ?>
	    </select></br>
	    Zmień rok wydania książki: <input type="number" name="year" placeholder="<?php echo $year; ?>" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo $year; ?>'" style="margin-left: 87px;"></br>
	    <input type="submit" name="submit" value="Zapisz" style="margin-left: 315px;"></br>
	</form>
    
    <?php if (isset($edited) && !$edited): ?>
        <p align="center">Zmień coś żeby edytować!</p>
    <?php elseif (isset($ok) && $ok && $edited): ?>
        <p align="center">Udało się edytować wpis w katalogu :)</p>
    <?php elseif (isset($ok) && !$ok): ?>
        <p align="center">Nie udało się edytować wpisu w katalogu :(</p>
    <?php endif; ?>

    </div>

    <div class="footer">
    </div>

</body>
</html>
