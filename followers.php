<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mes abonnés </title>
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <?php include 'header.php'; ?>
    <?php
    // Vérification de l'autorisation
    if (!$_SESSION['connected_id']) {
        // Si l'utilisateur n'est pas autorisé il est reconduit
        // sur le formulaire d'identification
        header("Location: login.php");
        die();
    }
?>
        <div id="wrapper">
            <aside>
                <img src = "user.jpg" alt = "Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez la liste des personnes qui
                        suivent les messages de <a href="wall.php?user_id=<?php echo intval($_GET['user_id']) ?>">l'utilisatrice
                        n° <?php echo intval($_GET['user_id']) ?></a></p>

                </section>
            </aside>
            <main class='contacts'>
                <?php
                // Etape 1: récupérer l'id de l'utilisateur
                $userId = intval($_GET['user_id']);
                // Etape 2: se connecter à la base de donnée
                include 'connexion_bdd.php';
                // Etape 3: récupérer le nom de l'utilisateur
                $laQuestionEnSql = "
                    SELECT users.*
                    FROM followers
                    LEFT JOIN users ON users.id=followers.following_user_id
                    WHERE followers.followed_user_id='$userId'
                    GROUP BY users.id
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Etape 4: à vous de jouer
                //@todo: faire la boucle while de parcours des abonnés et mettre les bonnes valeurs ci dessous
                while ($followers = $lesInformations->fetch_assoc())
                {
                   //echo "<pre>" . print_r($followers, 1) . "</pre>";
                ?>
                <article>
                    <img src="user.jpg" alt="blason"/>
                    <h3><a href="wall.php?user_id=<?php echo $followers['id'] ?>"> <?php echo $followers['alias'] ?> </a></h3>
                    <p>id:<?php echo $followers['id'] ?></p>
                </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
