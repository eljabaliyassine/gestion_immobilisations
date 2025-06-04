# Instructions de Déploiement - Gestion Immobilisations

Ce document décrit les étapes nécessaires pour déployer l'application de gestion des immobilisations sur un serveur avec une base de données MySQL.

## Prérequis

*   Serveur web (Apache ou Nginx)
*   PHP >= 8.1 (avec les extensions requises par Laravel : Ctype, cURL, DOM, Fileinfo, Filter, Hash, Mbstring, OpenSSL, PCRE, PDO, Session, Tokenizer, XML, PDO MySQL)
*   Composer
*   Serveur MySQL

## Étapes de Déploiement

1.  **Transférer les Fichiers :**
    *   Transférez le contenu de l'archive fournie (`gestion_immobilisations.zip`) sur votre serveur, dans le répertoire de votre choix (par exemple, `/var/www/immos.emexsoftware.com`).

2.  **Configurer la Base de Données MySQL :**
    *   Créez une nouvelle base de données MySQL (par exemple, `gestion_immobilisations`).
    *   Créez un utilisateur MySQL dédié et accordez-lui tous les privilèges sur cette base de données.

3.  **Configurer le Fichier d'Environnement (`.env`) :**
    *   Renommez ou copiez le fichier `.env.example` en `.env` s'il n'existe pas (normalement, le `.env` fourni est déjà configuré pour MySQL).
    *   Ouvrez le fichier `.env` et mettez à jour les informations de connexion à la base de données avec celles que vous venez de créer :
        ```
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=gestion_immobilisations
        DB_USERNAME=votre_utilisateur_mysql
        DB_PASSWORD=votre_mot_de_passe_mysql
        ```
    *   Assurez-vous que `APP_URL` correspond à l'URL publique de votre application (par exemple, `http://immos.emexsoftware.com`).
    *   Vérifiez que `APP_ENV` est défini sur `production` et `APP_DEBUG` sur `false`.

4.  **Installer les Dépendances PHP :**
    *   Naviguez vers le répertoire racine de l'application via le terminal.
    *   Exécutez la commande suivante pour installer les dépendances PHP (y compris Laravel) :
        ```bash
        composer install --optimize-autoloader --no-dev
        ```

5.  **Générer la Clé d'Application :**
    *   Si vous n'avez pas utilisé le fichier `.env` fourni, générez une nouvelle clé d'application :
        ```bash
        php artisan key:generate
        ```

6.  **Exécuter les Migrations de Base de Données :**
    *   Exécutez la commande suivante pour créer les tables dans votre base de données MySQL :
        ```bash
        php artisan migrate --force
        ```
    *   *Optionnel :* Si vous avez besoin de données de test initiales (rôles, etc.), vous devrez peut-être exécuter des *seeders* (non inclus par défaut dans ce projet).

7.  **Configurer le Serveur Web :**
    *   Configurez votre serveur web (Apache ou Nginx) pour qu'il pointe vers le répertoire `public` de l'application.
    *   Assurez-vous que les règles de réécriture d'URL sont correctement configurées pour Laravel.
    *   **Exemple de configuration Apache (Virtual Host) :**
        ```apache
        <VirtualHost *:80>
            ServerName immos.emexsoftware.com
            DocumentRoot /var/www/immos.emexsoftware.com/public

            <Directory /var/www/immos.emexsoftware.com/public>
                AllowOverride All
                Require all granted
            </Directory>

            ErrorLog ${APACHE_LOG_DIR}/immos-error.log
            CustomLog ${APACHE_LOG_DIR}/immos-access.log combined
        </VirtualHost>
        ```
    *   **Exemple de configuration Nginx (Server Block) :**
        ```nginx
        server {
            listen 80;
            server_name immos.emexsoftware.com;
            root /var/www/immos.emexsoftware.com/public;

            add_header X-Frame-Options "SAMEORIGIN";
            add_header X-Content-Type-Options "nosniff";

            index index.php;

            charset utf-8;

            location / {
                try_files $uri $uri/ /index.php?$query_string;
            }

            location = /favicon.ico { access_log off; log_not_found off; }
            location = /robots.txt  { access_log off; log_not_found off; }

            error_page 404 /index.php;

            location ~ \.php$ {
                fastcgi_pass unix:/var/run/php/php8.1-fpm.sock; # Adaptez à votre version/configuration PHP-FPM
                fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
                include fastcgi_params;
            }

            location ~ /\.(?!well-known).* {
                deny all;
            }
        }
        ```
    *   N'oubliez pas d'activer la configuration (par exemple, `a2ensite` pour Apache, créer un lien symbolique pour Nginx) et de redémarrer le serveur web.

8.  **Permissions des Dossiers :**
    *   Assurez-vous que les répertoires `storage` et `bootstrap/cache` ont les permissions d'écriture appropriées pour l'utilisateur du serveur web.
        ```bash
        sudo chown -R www-data:www-data /var/www/immos.emexsoftware.com/storage
        sudo chown -R www-data:www-data /var/www/immos.emexsoftware.com/bootstrap/cache
        sudo chmod -R 775 /var/www/immos.emexsoftware.com/storage
        sudo chmod -R 775 /var/www/immos.emexsoftware.com/bootstrap/cache
        ```
        (Adaptez `www-data` à l'utilisateur de votre serveur web si nécessaire).

9.  **Optimisations (Optionnel mais recommandé) :**
    *   Pour améliorer les performances en production, exécutez les commandes suivantes :
        ```bash
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
        ```

## Accès à l'Application

Une fois le déploiement terminé, vous devriez pouvoir accéder à l'application via l'URL configurée (par exemple, `http://immos.emexsoftware.com`).

Utilisez les identifiants administrateur fournis pour vous connecter.

