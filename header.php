<?php
echo session_status();
if (session_status() == 2) {
  session_start();
  $userId = intval($_SESSION['connected_id']);
}
?>

        <header>
            <img src="resoc.jpg" alt="Logo de notre réseau social"/>
            <nav id="menu">
                <a href="news.php">Actualités</a>
                <a href="wall.php?user_id=<?php echo $userId; ?>">Mur</a>
                <a href="feed.php?user_id=<?php echo $userId; ?> ">Flux</a>
                <a href="tags.php?tag_id=<?php echo $userId; ?> ">Mots-clés</a>
                <a href="registration.php">Inscription</a>
                <a href="usurpedpost.php">Message en mode non-identifié</a>
                <a href="destroy.php">Déconnexion</a>
            </nav>
            <nav id="user">
                <!-- Si tu es connecté.e on affiche onglet "Profil" sinon l'onglet "Login" -->
                <?php
                if (session_status() == 1) {
                 echo "<a href='login.php'>Login</a>";}
                else {
                echo
                "<a href='#'>Profil </a>
                <ul>
                    <li><a href='settings.php?user_id=". $userId ."'>Paramètres</a></li>
                    <li><a href='followers.php?user_id=". $userId ."''>Mes suiveurs</a></li>
                    <li><a href='subscriptions.php?user_id=". $userId ."'>Mes abonnements</a></li>
                </ul>"
                ;}
              ?>
            </nav>
        </header>
