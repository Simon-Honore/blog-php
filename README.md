# Blog PHP

Blog en PHP afin d'apprendre le langage. MySQL pour la database.
Formation de Grafikart.
Très bonne initiation à PHP et à la POO. Il pourrait être largement optimisé mais le but était de se concentrer sur la logique.
Site non responsive, le design n'est pas bon du tout (c'est mon avis), mais ce n'était pas le but du tout ici.
Je ne vais pas l'améliorer mais je pense refaire un blog moi-même en php, peut-être avec un framework, sans support de cours.

## Objectifs

Créer un blog avec un system de catégorie

- Page listing d'articles (pagination)
- Page listing d'articles pour une catégorie (pagination)
- Page présentation d'un article
- Administration des catégories
- Administration des articles
- upload d'images
  - Valider le fichier envoyé par l'utilisateur
  - Sauvegarder le fichier sur le serveur
  - Gérer la suppression du fichier (quand l'article est supprimé)

## Installation

1. Créer une base de donnée MySql

2. Cloner le repo

3. Créer un fichier `.env` à la racine

```
DATABASE_URL="mysql:dbname={name};host=127.0.0.1"
DATABASE_USERNAME=
DATABASE_PASSWORD=

ADMIN_USERNAME=
ADMIN_PASSWORD=
```

4. Composer

```zsh
composer install
composer dump-autoload
```

5. Remplir la base de données (sans images)

```zsh
php commands/fill.php
```

6. Lancer le serveur (sans MAMP)

```zsh
php -S localhost:3000 -t public
```
