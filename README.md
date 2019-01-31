# CRM

## Instructions d'installation

### Prerequisites

Vous devez au préalable avoir installer sur votre machine:

* [Git](https://git-scm.com/downloads) - Git est un logiciel de gestion de versions décentralisé.
* [Composer](https://getcomposer.org/doc/00-intro.md) - Composer est un outil de gestion de la dépendance en PHP.

### Installation

1. Ouvrez votre invité de commande préféré et placer vous dans le répertoire racine de votre server.

2. Tapez la commande suivante:

	```
	git clone https://github.com/altane/tests_recrutements.git
	```

3. Puis:

	```
	cd tests_recrutements && composer install
	```
4. Créez une base de donnée nommé "leboncoin" dans votre phpmyadmin ou dans votre editeur de base de donnée.

5. Executez le fichier "upgrade.sql" puis "optimisation_bdd.sql" sur la base de donnée créer.

6. Éditez le fichier "app/Config.php" et remplacez les parametres "db_user", "db_pass", "db_host", "db_name" par vos paramètre de connexion à votre base de donnée.

7. Pour finir, ajoutez un Virtual Host sur votre serveur apache pointant sur le dossier cloné.