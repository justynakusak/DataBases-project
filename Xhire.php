<?php  
    require 'connection.php';
    $pdo = connection();

    $bookId = trim($_GET['id']);
    $login = trim($_GET['login']);
		
try{   
	$pdo->beginTransaction();
    $query = 'SELECT wypozyczona FROM ksiazki WHERE id = :bookId FOR UPDATE';
    if ($sth = $pdo->prepare($query)) {
        $sth->bindValue(':bookId', $bookId, PDO::PARAM_INT);
        if (!$sth->execute()) 
            print_r($pdo->errorInfo()); 
        else {
            $result = $sth->fetch();
            echo $result['wypozyczona'];
       
            if($result['wypozyczona']==0) {
                $query = 'UPDATE ksiazki SET wypozyczona = 1 WHERE id = :bookId';
                if ($sth = $pdo->prepare($query)) {
                    $sth->bindValue(':bookId', $bookId, PDO::PARAM_INT);
                    if (!$sth->execute()) 
                        print_r($pdo->errorInfo());    
                    else{
                        $query = 'UPDATE uzytkownicy SET id_ksiazki = :bookId WHERE nazwa = :login';
                        if ($sth = $pdo->prepare($query)) {
                            $sth->bindValue(':bookId', $bookId, PDO::PARAM_INT);
                            $sth->bindValue(':login', $login, PDO::PARAM_INT);
                            if (!$sth->execute()) 
                                print_r($pdo->errorInfo());
                            else {
                                $pdo->commit();
                                header('Location: Xuser.php?login=' . $login);
                            }
                        }
                    }
                }
            }
            else {
                $msg = 'Nie można wypożyczyć. Ktoś zrobił to wcześniej :(';
                header('Location: Xuser.php?login=' . $login . '&msg=' . $msg);
            }
        }
    }
}catch (Exception $e) {
    $pdo->rollBack();
    echo "Failed: " . $e->getMessage();
    print_r($pdo->errorInfo());
}		

?>