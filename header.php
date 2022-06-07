 <!-- Si tu es connecté.e on affiche onglet "Profil" sinon l'onglet "Login" -->
                <?php
                if (!isset($_SESSION['connected_id'])) {
                
                 echo "<header>
                 <img src='resoc.jpg' alt='Logo de notre réseau social'/>
                 <nav id='menu'>
                     <a href='news.php'>Actualités</a>
                     <a href='feed.php'>Flux</a>
                     <a href='registration.php'>Inscription</a>
                     <a href='usurpedpost.php'>Message en mode non-identifié</a>
                 </nav>
                <nav id='user'>
                 <a href='login.php'>Login</a>
                 </nav>
                </header>";
        
                }
                else {
                echo "<header>
                <img src='resoc.jpg' alt='Logo de notre réseau social'/>
                <nav id='menu'>
                    <a href='news.php'>Actualités</a>
                    <a href='wall.php?user_id=". $_SESSION['connected_id']."'>Mur</a>
                    <a href='feed.php?user_id=". $_SESSION['connected_id']."'>Flux</a>
                    <a href='usurpedpost.php'>Message en mode non-identifié</a>
                </nav>
                <nav id='user'>
               <a href='#'>Profil </a>
                <ul>
                    <li><a href='settings.php?user_id=".  $_SESSION['connected_id'] ."'>Paramètres</a></li>
                    <li><a href='followers.php?user_id=".  $_SESSION['connected_id'] ."''>Mes suiveurs</a></li>
                    <li><a href='subscriptions.php?user_id=".  $_SESSION['connected_id'] ."'>Mes abonnements</a></li>
                    <li><a href='destroy.php'>Déconnexion</a></li>
                    </ul>
                </nav>
                </header>"
                ;}
              ?>
