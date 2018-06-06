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
	<h1 align="center">Katalog książek</h1>      
        
	<?php
	    $sth = $pdo->query('SELECT * FROM ksiazki order by tytul;');
	    $result = $sth->fetchAll();
	?>
		
    <table class="table" cellpadding="15" >
      <thead>
        <tr align ="center">
          <th scope="col">Tytuł</th>
          <th scope="col">Autor</th>
          <th scope="col">Kategoria</th>
          <th scope="col">Rok Wydania</th>
          <th scope="col">Wypożyczona</th>
    
        </tr>
      </thead>
      <tbody>
      <?php foreach ($result as $row): ?>
      <tr align="center">
          <td><?php echo $row["tytul"]; ?></td>
          <td><?php echo $row["autor"]; ?></td>
          <td><?php echo $row["kategoria"]; ?></td>
          <td><?php echo $row["rok_wydania"]; ?></td>
          <td><?php if($row["wypozyczona"]==1):?> <p style="color: red; margin: 0px;">tak</p><?php endif; ?>
          <?php if($row["wypozyczona"]==0):?> <p style="color: green; margin: 0px;">nie</p><?php endif; ?></td>
		  <td><a class="butt" href="Xedit.php?id=<?php echo $row["id"]; ?>">Edytuj</a></td>
		  <td><a class="butt" href="Xdelete.php?id=<?php echo $row["id"]; ?>">Usuń</a></td>
	  </tr>
      <?php endforeach; ?>
      </tbody>
    </table> 

    </div>

    <div class="footer">
    </div>

</body>
</html>

   
