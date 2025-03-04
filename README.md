# Projet Symfony avancé : KUB

## Installation

1. Cloner le projet
2. Installer PHP >= 8.2 et Composer (Sur votre machine utiliser XAMPP pour windows, MAMP pour mac ou LAMP pour linux bien prendre la version PHP 8.2)
3. Installer les dépendances du projet avec la commande `composer install`
4. Faire un virtual host sur votre serveur local (XAMPP par exemple pour Windows) 
 - Ouvrir le fichier `httpd-vhosts.conf` dans le répertoire `C:\xampp\apache\conf\extra`
    - Ajouter le code suivant à la fin du fichier
    ```
    <VirtualHost *>
        DocumentRoot "[chemin d'installation]\public"
        ServerName symfony_base.local
        
        <Directory "[chemin d'installation]\public">
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>
    ```
    - Ajouter l'adresse IP de votre machine dans le fichier `C:\Windows\System32\drivers\etc\hosts`
    ```
    127.0.0.1 symfony_base.local
    ```
    - Redémarrer Apache
    - Accéder à l'adresse `symfony_base.local` dans votre navigateur

4. Créer un fichier `.env.local` à la racine du projet et ajouter la configuration de la base de données
5. Créer la base de données avec la commande `php bin/console doctrine:database:create`
6. Executer  les fixtures avec la commande `php bin/console doctrine:fixtures:load`
7. Executer la commande `app:import-csv` pour charger les produits depuis le CSV

## Fonctionnalités

- Gestion des utilisateurs,  produits, clients
- Formulaires et index pour chaque (notament affichage trié pour les produits)
- Export et import des produits en CSV
- Fixtures pour utilisateurs et clients
- Commande pour ajouter un clients (avec validation des champs)
- Gestion des permissions avec les voters
- Tests du service permettant la validation des champs

## Informations

### Comptes
- Admin : lucas.jordan@admin.fr - adminpass
- Manager : lirasu@gmail.com - managerpass
- User : manumacs@gmail.com - userpass

### Commandes

- `php bin/console app:add-client` : Ajout de client
- `php bin/console app:import-csv`: Importation de produit en CSV, le fichier CSV doit être dans public/products.csv, un fichier de test s'y trouve déjà

### Tests

- Pour lancer les tests : .\vendor\bin\phpunit