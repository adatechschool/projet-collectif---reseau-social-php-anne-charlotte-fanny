 <!-- Si tu es connecté.e on affiche onglet "Profil" sinon l'onglet "Login" -->
                <?php
                if (!isset($_SESSION['connected_id'])) {

                echo "<header>

                
                <nav id='menu'>
                <a href='news.php'><img src='cassette-radio.png'/></a>
                    <a href='news.php'>ACTUALITES</a>
                    <a href='feed.php'>FLUX</a>
                    <a href='registration.php'>INSCRIPTION</a>
                    <a href='usurpedpost.php'>MESSAGE ANONYME</a>
                </nav>
                <nav id='user'>
                 <a href='login.php'>LOGIN</a>
                 </nav>
                </header>";

                }
                else {
                echo "<header>
                <nav id='menu'>
                    <a href='news.php'><img src='cassette-radio.png'/></a>
                    <a href='news.php'>ACTUALITES</a>
                    <a href='wall.php?user_id=". $_SESSION['connected_id']."'>MUR</a>
                    <a href='feed.php?user_id=". $_SESSION['connected_id']."'>FLUX</a>
                    <a href='usurpedpost.php'>MESSAGE ANONYME</a>
                </nav>
                <nav id='user'>
               <a href='#'>PROFIL </a>
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
