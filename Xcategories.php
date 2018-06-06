<?php
    require 'connection.php';
    $pdo = connection();

    if (isset($_POST['submit'])) {
        $selCateg = trim($_POST['category']);
        $query = 'SELECT tytul, autor, kategoria, rok_wydania FROM ksiazki where kategoria = :selCateg order by tytul;';
        if ($sth = $pdo->prepare($query)) {
            $sth->bindValue(':selCateg', $selCateg, PDO::PARAM_STR);			
            if ($sth->execute() && $sth->rowCount() > 0) 
                $books = $sth->fetchAll();
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
	<h1 align="center">Znajdź książki z wybranej kategorii:</h1>

	<form method="post" action="">
	<label>Wybierz kategorie:</label>
	<select name="category" style="margin-top: 0px;">
        <option selected disabled hidden value="<?php echo $selCateg; ?>"><?php echo $selCateg; ?></option>
        <?php foreach ($result as $row): ?>
        <option value="<?php echo $row["nazwa"]; ?>"><?php echo $row["nazwa"]; ?></option>
        <?php endforeach; ?>
	</select>
    <input type="submit" name="submit" value="szukaj" style="width:150px; font-size: 20px; padding: 12px; margin-top: 0px;">
	<p></p>
		
    <table class="table" cellpadding="15" >
        <thead>
            <tr align ="center">
                <th scope="col">Tytuł</th>
                <th scope="col">Autor</th>
                <th scope="col">Kategoria</th>
                <th scope="col">Rok Wydania</th>
            </tr>
        </thead>
        
        <tbody>
            <?php if (isset($books)): ?>
            <?php foreach ($books as $row): ?>
            <tr align="center">
                <td><?php echo $row["tytul"]; ?></td>
                <td><?php echo $row["autor"]; ?></td>
                <td><?php echo $row["kategoria"]; ?></td>
                <td><?php echo $row["rok_wydania"]; ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table> 

    </div>

    <div class="footer">
    </div>

</body>
</html>
