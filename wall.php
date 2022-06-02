<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mur</title>
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <?php include 'header.php'; ?>

        <div id="wrapper">
            <?php
            /**
             * Etape 1: Le mur concerne un utilisateur en particulier
             * La première étape est donc de trouver quel est l'id de l'utilisateur
             * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
             * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
             * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
             */
            $userId =intval($_GET['user_id']);
            ?>
            <?php
            /**
             * Etape 2: se connecter à la base de donnée
             */
            include 'connexion_bdd.php';
            ?>

            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 */
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
                //echo "<pre>" . print_r($user, 1) . "</pre>";
                ?>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les message de <a href="wall.php?user_id=<?php echo intval($_GET['user_id']) ?>">l'utilisatrice <?php echo $user['alias']?>
                        (n° <?php echo $userId ?>)</a>
                    </p>
                <form action="wall.php?user_id=<?php echo $userId; ?>" method="post">
                <button class="abonnement"
                type="button">
                S'abonner
                </button>
                </form>

                   
                </section>
            </aside>
            <main>

                <?php
                /**
                 * Etape 3: récupérer tous les messages de l'utilisatrice
                 */
                $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name, users.id as author_id,
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id
                    LEFT JOIN likes      ON likes.post_id  = posts.id
                    WHERE posts.user_id='$userId'
                    GROUP BY posts.id
                    ORDER BY posts.created DESC
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                /**
                 * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
                 */
                ?>
                <?php 
                //Requête de la liste des tags pour le formulaire 
                 $listTags = [];
                 $laQuestionEnSqlTag = "SELECT * FROM tags";
                 $lesInformationsTags = $mysqli->query($laQuestionEnSqlTag);
                 while ($tag = $lesInformationsTags->fetch_assoc())
                 {
                     $listTags[$tag['id']] = $tag['label'];
                 }
                /**
                  * TRAITEMENT DU FORMULAIRE pour créer un message
                  */
                  // On vérifie si un message est envoyé
                 $enCoursDeTraitement = isset($_POST['message']);
                 if ($enCoursDeTraitement)
                 {
                     // Si message envoyé on récupère les données du formulaire
                     echo "<pre>" . print_r($_POST, 1) . "</pre>";
                     $new_authorid = $_POST['auteur'];
                     $new_message = $_POST['message'];
                     $new_tag = $_POST['tag'];
                  
                     //Ouvrir une connexion avec la base de donnée.
                     include 'connexion_bdd.php';
                     //Sécurité
                     // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                     $new_authorid = $mysqli->real_escape_string($new_authorid);
                     $new_message = $mysqli->real_escape_string($new_message);
                     $new_tag = $mysqli->real_escape_string($new_tag);
                    
                     //Construction de la requete
                     $lInstructionSql = "INSERT INTO posts (id, user_id, content, created, parent_id) "
                
                             . "VALUES (NULL, "
                             . "'" . $new_authorid . "', "
                             . "'" . $new_message . "', "
                             . "NOW(), "
                             . "NULL);"
                             ;
 
                     // Exécution de la requete
                     $ok = $mysqli->query($lInstructionSql);
                     if ( ! $ok)
                     {
                         echo "Le message n'a pas été enregistré : " . $mysqli->error;
                     } else
                     { 
                         echo "Message enregistré ";
                         $post_id = $mysqli->insert_id;
                         echo $post_id ;
                         $lInstructionSqlTag = "INSERT INTO posts_tags (id, post_id , tag_id) "
                
                             . "VALUES (NULL, "
                             . "'" . $post_id . "', "
                             . "'" . $new_tag . "'); "
                             ;
                        $okTag = $mysqli->query($lInstructionSqlTag);
                       
                        if ( ! $okTag)
                        {
                            echo "Le tag n'a pas été enregistré : " . $mysqli->error;
                        } else
                        { 
                            echo "Tag enregistré ";
                        }
                     }
                 }
                
                ?>
                <article>
                  <form action="wall.php?user_id=<?php echo $userId; ?>" method="post">
                        <input type='hidden' name='auteur' value='<?php echo $userId?>'>
                        <dl>
                            <dt><label for='auteur'> Auteur: <?php echo $_SESSION['connected_alias'];?> </label></dt>
                            <dt><label for='message'>Message</label></dt>
                            <dd><textarea name='message'></textarea></dd>
                            <dd><select name='tag'>
                                  <?php 
                                   foreach ($listTags as $id => $label)
                                        echo "<option value='$id'>$label</option>";
                                    ?>
                            </select></dd> 
                        </dl>
                        <input type='submit'>
                  </form>
                </article>

                <?php
                while ($post = $lesInformations->fetch_assoc())
                {

                    //echo "<pre>" . print_r($post, 1) . "</pre>";
                    ?>

                    <article>
                        <h3>
                            <time datetime='<?php echo $post['created'] ?>' >
                            <?php
                            setlocale(LC_TIME, "fr_FR","French");
                            echo strftime("%d %B %G à %Hh%M", strtotime($post['created']));?>
                            </time>
                        </h3>
                        <address><a href="wall.php?user_id=<?php echo $post['author_id'] ?>"><?php echo "par ".$post['author_name'] ?></a></address>
                        <div>
                            <p><?php echo $post['content'] ?></p>
                        </div>
                        <footer>
                            <small>♥ <?php echo $post['like_number'] ?>
                            </small>
                            <a href="">
                            <?php
                            $array = explode(',', $post['taglist']);
                            foreach ($array as $valeur) {
                                echo "<a href=''>#$valeur, </a>";}
                            ?></a>
                        </footer>
                    </article>
                <?php } ?>


            </main>
        </div>
    </body>
</html>
