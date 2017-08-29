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

$nbre_msg = $bdd->query('SELECT count(id) AS nb_msg FROM minichat'); // On récupère le nombre de messages dans la table minichat de la BDD
$nbre_msg = $nbre_msg->fetch();
$nbre_page = $nbre_msg['nb_msg'] / 10;
if($nbre_msg % 10 != 0)
    $nbre_page += 1;
?>

<!--Formulaire permettant de choisir la page d'anciens messages à afficher -->
<form method="post" action="minichat.php">
    <?php
echo "Pages : ";
if(isset($_POST['page']))
{
    $limit = ($_POST['page'] - 1)* 10;
    for ($i=1; $i < $nbre_page; $i++) { 
    if($_POST['page'] == $i)
        echo '<input type="radio" name="page" value="'.$i.'" id="page'.$i.'" checked="checked"/><label for="page'.$i.'">page '.$i.' </label>';
    else
        echo '<input type="radio" name="page" value="'.$i .'" id="page'.$i.'" /><label for="page'.$i.'">page '.$i.' </label>';
    }
}
else
{
    $limit = 0;
    for ($i=1; $i < $nbre_page; $i++) { 
    if($i == 1)
        echo '<input type="radio" name="page" value="1" id="page1" checked="checked"/><label for="page1">page 1 </label>';
    else
        echo '<input type="radio" name="page" value="'.$i .'" id="page'.$i.'" /><label for="page'.$i.'">page '.$i.' </label>';
    }
}    
?>
<input type="submit" name="Afficher" value="Afficher">
</form>

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