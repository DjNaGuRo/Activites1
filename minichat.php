<?php 
session_start();
$_SESSION['pseudo'] = 'DjNaGuRo';
setcookie('pseudo',$_SESSION['pseudo'], time() + 30 * 24 *3600, null, null, false, true);
?>

<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8" />

        <title>Mini-chat</title>

    </head>

    <style>

    form

    {

        text-align:center;

    }

    </style>

    <body>

    

    <form action="minichat_post.php" method="post">

        <p>

        <?php 
        if(isset($_COOKIE['pseudo']))
        { 
        ?>
            <label for="pseudo">Pseudo</label> : 
            <input type="text" name="pseudo" id="pseudo" value="<?php echo $_COOKIE["pseudo"]; ?>"/> .<br /><br />
        <?php
       }
        else
            echo '<label for="pseudo">Pseudo</label> : <input type="text" name="pseudo" id="pseudo" /><br /><br />';
        ?>

        <label for="message">Message</label> :  <input type="text" name="message" id="message" /><br /><br />


        <input type="submit" value="Envoyer" />

    </p>

    </form>


<?php

// Connexion à la base de données

try

{

    $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '');

}

catch(Exception $e)

{

        die('Erreur : '.$e->getMessage());

}


// Récupération des 10 derniers messages

$reponse = $bdd->query('SELECT pseudo, message FROM minichat ORDER BY ID DESC LIMIT 0, 10');


// Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)

while ($donnees = $reponse->fetch())

{

    echo '<p><strong>' . htmlspecialchars($donnees['pseudo']) . '</strong> : ' . htmlspecialchars($donnees['message']) . '</p>';

}


$reponse->closeCursor();


?>

    </body>

</html>