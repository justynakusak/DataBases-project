<?php  
    require 'connection.php';
    $pdo = connection();

    $delId = trim($_GET['id']);
		
	$query = 'DELETE FROM ksiazki WHERE id =:delId';
    if ($sth = $pdo->prepare($query)) {
        $sth->bindValue(':delId', $delId, PDO::PARAM_INT);
        if (!$sth->execute()) {
            echo 'Błąd serwera';
            print_r($pdo->errorInfo());
        }
        else
            header('Location: Xmain.php');
    }	 		
?>
		


        
		
