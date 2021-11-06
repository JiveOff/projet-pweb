# Projet PWEB: Location de voitures

Le but de ce projet de PWEB a été de créer un site de gestion de stocks automobiles pour un loueur et ses clients.
Ce site permettrait alors de consulter un stock de voiture géré par un loueur sur une interface facile à manier et que les clients puissent gérer leurs locations.

Une visualisation des factures est également réalisée.

### Rapport de projet

Vous pouvez retrouver le rapport du projet dans la racine du projet.

### Installer avec Docker

L'avantage avec Docker est que vous n'avez pas besoin de configurer quoi que ce soit et que tout le projet s'installe tout seul.

Vous pouvez suivre un tutoriel d'installation ici: https://codedesign.fr/docker-desktop-windows-debutant/

Pendant l'installation de Docker, vous pouvez télécharger les dépendances via Composer, pour cela, ouvrez un terminal dans le dossier du projet et saisissez la commande:

``composer install``

**Une fois Docker installé:**

Tapez dans le terminal la commande suivante:

``docker-compose up -d``

Une fois la construction de l'image du serveur Web et l'installation terminée, l'application devrait être en ligne à l'adresse **localhost:8080**.

Les performances à la première visite peuvent varier car le cache est en train d'être rempli dans le conteneur.

### Installer sans Docker

Ouvrez un terminal dans le dossier du projet.

Installez les dépendances via Composer en tapant dans le terminal:

``composer install``

Vérifiez que vous avez la bonne version de PHP (7.4) en tapant:

``php -v``

Modifiez la ligne 35 du **.env** pour y inclure la configuration de votre base de données:

``DATABASE_URL=mysql://UTILISATEUR:MOTDEPASSE@HOTE:PORT/BASE_DE_DONNEE``

Importez le fichier **app_db.sql** dans votre base de donnée MySQL.

Démarrez le serveur Symfony:

``symfony server:start``

### Identifiants

Mail: admin@location.com ou test1@location.com ou test2@location.com ou test3@location.com

Mot de passe: `test` (valable sur tous les comptes ci-dessus)