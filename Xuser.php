<?php
    require 'connection.php';
    $pdo = connection();
        
    $login = trim($_GET['login']);
    if(isset($_GET['msg']))
        print '<script type="text/javascript">alert("' . $_GET['msg'] . '");</script>';
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
	<h1 align="center">Witaj <?php echo $login; ?></h1>      
             
<?php   
        
    $query = 'SELECT id_ksiazki FROM uzytkownicy WHERE nazwa = :login';
    if ($sth = $pdo->prepare($query)) {
        $sth->bindValue(':login', $login, PDO::PARAM_STR);
        if (!$sth->execute()) {
            print_r($pdo->errorInfo());
			push_array($error, 'Błąd serwera');
		}
        else {
            $result = $sth->fetch();
            $bookId = $result["id_ksiazki"];

            if($bookId){
                $status = false;
                $query = 'SELECT id, tytul, autor, kategoria, rok_wydania FROM ksiazki WHERE id = :bookId';
                if ($sth = $pdo->prepare($query)) {
                    $sth->bindValue(':bookId', $bookId, PDO::PARAM_INT);
                    if (!$sth->execute()) 
                        print_r($pdo->errorInfo());
                    else
                        $result = $sth->fetchAll(); 
                }
            }
            else {
                $status = true;
                $sth = $pdo->query('SELECT * FROM ksiazki order by tytul;');
                $result = $sth->fetchAll();
            }
        }
    }      
?>
     
    <?php if(!$status): ?>  
    Wypożyczyłeś jedną książkę:
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
      <?php foreach ($result as $row): ?>
      <tr align="center">
          <td><?php echo $row["tytul"]; ?></td>
          <td><?php echo $row["autor"]; ?></td>
          <td><?php echo $row["kategoria"]; ?></td>
          <td><?php echo $row["rok_wydania"]; ?></td>
          <td><a class="butt" href="Xunhire.php?id=<?php echo $row["id"]; ?>&login=<?php echo $login; ?>">Zwróć</a></td>
	  </tr>
      <?php endforeach; ?>
      </tbody>
    </table> 
    <?php endif; ?>         
      
    <?php if($status): ?>  
    Nie wypożyczyłeś jeszcze żadnej książki. Możesz wypożyczyć jedną :)
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
      <?php foreach ($result as $row): ?>
      <tr align="center">
          <td><?php echo $row["tytul"]; ?></td>
          <td><?php echo $row["autor"]; ?></td>
          <td><?php echo $row["kategoria"]; ?></td>
          <td><?php echo $row["rok_wydania"]; ?></td>
          <td><?php if($row["wypozyczona"]==0): ?> 
		      <a class="butt" href="Xhire.php?id=<?php echo $row["id"]; ?>&login=<?php echo $login; ?>">Wypożycz</a>
          <?php endif; ?>
          <?php if($row["wypozyczona"]==1): ?> 
              Wypożyczona
          <?php endif; ?></td>
	  </tr>
      <?php endforeach; ?>
      </tbody>
    </table> 
    <?php endif; ?>

    </div>

    <div class="footer">
    </div>

</body>
</html>

