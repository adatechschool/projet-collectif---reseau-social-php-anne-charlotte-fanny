<?php session_start(); ?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Post d'usurpateur</title>
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    </head>
    <body>
    <?php include 'header.php'; ?>

        <div id="wrapper" >

            <aside>
                <img src="unknown.png" alt="Portrait de l'utilisatrice"/>
                <h3>Formulaire anonyme</h2>
                <p>Vous n'assumez pas votre chanson préférée, mais vous voulez la partager ? </p>
                <p>Vous êtes au bon endroit ! 🎵 </p>
            </aside>
            <main id="usurpedpost">
                <article>
                    <h2 style="font-weight: bold; color: #3E2EA6;">Poster un message</h2>
                    <?php
                    /* BD */
                    include 'connexion_bdd.php';
                    /* Récupération de la liste des auteurs */
                    $listAuteurs = [];
                    $laQuestionEnSql = "SELECT * FROM users";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    while ($user = $lesInformations->fetch_assoc())
                    {
                        $listAuteurs[$user['id']] = $user['alias'];
                    }


                    /* TRAITEMENT DU FORMULAIRE */
                    // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                    // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                    $enCoursDeTraitement = isset($_POST['auteur']);
                    if ($enCoursDeTraitement)
                    {
                        // on ne fait ce qui suit que si un formulaire a été soumis.
                        // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                        // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                        //echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        // et complétez le code ci dessous en remplaçant les ???
                        $authorId = $_POST['auteur'];
                        $postContent = $_POST['message'];


                        //Etape 3 : Petite sécurité
                        // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $authorId = intval($mysqli->real_escape_string($authorId));
                        $postContent = $mysqli->real_escape_string($postContent);
                        //Etape 4 : construction de la requete
                        $lInstructionSql = "INSERT INTO posts "
                                . "(id, user_id, content, created, parent_id) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW(), "
                                . "NULL);"
                                ;
                        //echo $lInstructionSql;
                        // Etape 5 : execution
                        $ok = $mysqli->query($lInstructionSql);
                        if ( ! $ok)
                        {
                            echo "Impossible d'ajouter le message: " . $mysqli->error;
                        } else
                        {
                          header("Location:news.php");
                        }
                    }
                    ?>
                    <fieldset style="border-color: #3E2EA6;">
                      <legend style="font-weight: bold; font-size: 1em; color: #3E2EA6; padding: 3px;"> Ecrivez de manière anonyme </legend>
                      <form action="usurpedpost.php" method="post">
                        <input type='hidden' name='auteur' value='19'>
                        <dl>
                            <dt style="padding-bottom: 10px;"><label style="font-weight: bold; font-size: 1em; color: #3E2EA6;" for='message'> Message : </label></dt>
                            <dd><textarea rows="5" cols="100" name='message'></textarea></dd>
                        </dl>
                        <input class ="submitButton" type='submit'>
                    </form>
                    </fieldset>
                </article>
            </main>
        </div>
    </body>
</html>
