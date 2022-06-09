<?php session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Actualités</title>
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    </head>
    <body>
    <?php include 'header.php'; ?>

        <div id="wrapper">
            <aside>
                <img src="news.png" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Bienvenue sur SocialSong</h3>
                    <p id="presentation">Sur cette page vous trouverez les dernières trouvailles musicales des utilisateurs de SocialSong. </p>
                </section>
            </aside>
            <main>
                <!-- L'article qui suit est un exemple pour la présentation et
                  @todo: doit etre retiré -->
                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13' >31 février 2010 à 11h12</time>
                    </h3>
                    <address>par Anne</address>
                    <div>
                        <p>Bim bam toi</p>
                        <p>Carla</p>
                        <p id="citation">"Et ça fait bim bam boom"</p>
                        <!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
                    <br>
                    <div id="player">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/pjJ2w1FX_Wg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>

                    </div>
                    <footer>
                        
                        <small id="exemple">1012 </small>
                        <small>
                              <form action="news.php" method="post">
                                <input type="hidden" name="liker_id" value="<?php echo $_SESSION['connected_id']?>">
                                <input type="hidden" name="post_id" value= "<?php echo $post['id'] ?>">
                                <button style="border: none; background-color: white; text-decoration: none; display: inline-block; padding: 5px;" type="submit">
                                <img src="love.png" alt="" style="float: left; padding-right: 0.5em; width:5%;" /></button>

                              </form>
                            </small>
                        <a href="">#culture</a>,
                    </footer>
                </article>

                <?php
                /*
                  // C'est ici que le travail PHP commence
                  // Votre mission si vous l'acceptez est de chercher dans la base
                  // de données la liste des 5 derniers messsages (posts) et
                  // de l'afficher
                  // Documentation : les exemples https://www.php.net/manual/fr/mysqli.query.php
                  // plus généralement : https://www.php.net/manual/fr/mysqli.query.php
                 */

                // Etape 1: Ouvrir une connexion avec la base de donnée.
                include 'connexion_bdd.php';
                //verification
                if ($mysqli->connect_errno)
                {
                    echo "<article>";
                    echo("Échec de la connexion : " . $mysqli->connect_error);
                    echo("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                    echo "</article>";
                    exit();
                }

                // Etape 2: Poser une question à la base de donnée et récupérer ses informations
                // cette requete vous est donnée, elle est complexe mais correcte,
                // si vous ne la comprenez pas c'est normal, passez, on y reviendra
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.id,
                    posts.created,
                    users.id  as author_id,
                    users.alias as author_name,
                    count(likes.id) as like_number,
                    tags.id as tagId,
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id
                    LEFT JOIN likes      ON likes.post_id  = posts.id
                    GROUP BY posts.id
                    ORDER BY posts.created DESC
                    LIMIT 50
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Vérification
                if ( ! $lesInformations)
                {
                    echo "<article>";
                    echo("Échec de la requete : " . $mysqli->error);
                    echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                    exit();
                }

                // Etape 3: Parcourir ces données et les ranger bien comme il faut dans du html
                // NB: à chaque tour du while, la variable post ci dessous reçois les informations du post suivant.
                while ($post = $lesInformations->fetch_assoc())
                {
                    //la ligne ci-dessous doit etre supprimée mais regardez ce
                    //qu'elle affiche avant pour comprendre comment sont organisées les information dans votre
                    // echo "<pre>" . print_r($post,1) . "</pre>";

                    // @todo : Votre mission c'est de remplacer les AREMPLACER par les bonnes valeurs
                    // ci-dessous par les bonnes valeurs cachées dans la variable $post
                    // on vous met le pied à l'étrier avec created
                    //
                    // avec le ? > ci-dessous on sort du mode php et on écrit du html comme on veut... mais en restant dans la boucle
                    ?>
                    <article>
                        <h3>
                            <time><?php echo $post['created'] ?></time>
                        </h3>
                        <address>par <a href="wall.php?user_id=<?php echo $post['author_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                        <div>
                            <p><?php echo $post['content'] ?></p>
                        </div>
                        <footer>
                          <?php
                            $enCoursDeTraitement = isset($_POST['liker_id']) // 1ère condition : On like le post
                                                  && $_POST['post_id'] == $post['id'];
                                                  // 2ème condition : Lorsque la boucle passe à l'ID du post liké, on rajoute le like mais pas sur les autres passage de boucle.
                          if ($enCoursDeTraitement) {
                            $new_likerId = $_POST['liker_id'];
                            $new_postId = $_POST['post_id'];

                            $new_likerId  = intval($mysqli->real_escape_string($new_likerId));
                            $new_postId  = intval($mysqli->real_escape_string($new_postId));                        //Etape 4 : construction de la requete
                            $lInstructionSql = "INSERT INTO likes (id, user_id, post_id) "
                                . "VALUES (NULL, "
                                . $new_likerId . ", "
                                . $new_postId . "); "
                                ;
                        // Etape 5 : execution
                        $ok = $mysqli->query($lInstructionSql);
                        if ( ! $ok)
                        {
                            echo "⚠️" . $mysqli->error;
                        } else
                        {
                            //header('refresh:0'); NE FONCTIONNE PAS A PARTIR DU DEUXIEME NE PEUT ETRE UTILISE : BONNE PRATIQUE
                            // FAIRE TOUS LE PHP AYANT BESOIN DU HEADER AVANT LA PARTIE AFFICHAGE
                            $post['like_number'] += 1;
                            echo "👍";
                        }
                          } ?>
                          
                          <small id="numberlikes"><?php echo $post['like_number'] ?> </small>
                    <?php
                    if (isset($_SESSION['connected_id'])) { ?>
                            <!-- Formulaire "bouton ♥" Front -->
                            <small>
                              <form action="news.php" method="post">
                                <input type="hidden" name="liker_id" value="<?php echo $_SESSION['connected_id']?>">
                                <input type="hidden" name="post_id" value= "<?php echo $post['id'] ?>">
                                <button style="border: none; background-color: white; text-decoration: none; display: inline-block; padding: 5px;" type="submit">
                                <img src="love.png" alt="" style="float: left; padding-right: 0.5em; width:5%;" /></button>

                              </form>
                            </small>
                        <?php } ?>

                            <?php
                            $array = explode(',', $post['taglist']);
                            foreach ($array as $valeur) {
                                echo "<a href='tags.php?tag_id=". $post['tagId']."'> #$valeur, </a>";}
                            ?>
                        </footer>
                    </article>
                    <?php
                    // avec le <?php ci-dessus on retourne en mode php
                }// cette accolade ferme et termine la boucle while ouverte avant.
                ?>

            </main>
        </div>

    </body>
</html>
