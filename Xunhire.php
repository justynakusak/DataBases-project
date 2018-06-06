<?php  
    require 'connection.php';
    $pdo = connection();

    $bookId = trim($_GET['id']);
    $login = trim($_GET['login']);	
        
    $query = 'UPDATE ksiazki SET wypozyczona = 0 WHERE id = :bookId';
    if ($sth = $pdo->prepare($query)) {
        $sth->bindValue(':bookId', $bookId, PDO::PARAM_INT);
        if (!$sth->execute()) 
            print_r($pdo->errorInfo());    
        else {
            $query = 'UPDATE uzytkownicy SET id_ksiazki = null WHERE nazwa = :login';
            if ($sth = $pdo->prepare($query)) {
                $sth->bindValue(':login', $login, PDO::PARAM_INT);
                if (!$sth->execute()) 
                    print_r($pdo->errorInfo());
            }
        }
        header('Location: Xuser.php?login=' . $login);
    }		
?>