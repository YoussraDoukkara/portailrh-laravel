# Configuration

1. Copiez le projet Laravel dans le répertoire du serveur Web de votre localhost.

2. Mettez à jour le fichier .env :

```bash
APP_URL=http://votre_url_d_application
WEB_APP_URL=http://votre_url_d_application

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=votre_base_de_données
DB_USERNAME=votre_nom_utilisateur
DB_PASSWORD=votre_mot_de_passe

MAIL_DRIVER=smtp
MAIL_HOST=votre_serveur_mail
MAIL_PORT=votre_port_mail
MAIL_USERNAME=votre_nom_utilisateur_mail
MAIL_PASSWORD=votre_mot_de_passe_mail
MAIL_ENCRYPTION=votre_cryptage_mail
MAIL_FROM_ADDRESS=votre_adresse_mail_expéditeur
MAIL_FROM_NAME="${APP_NAME}"
```

3. Installez les dépendances requises :

```bash
composer install
```

4. Effectuez la migration et le remplissage de la base de données :

```bash
php artisan migrate --seed
```

5. Exécutez la commande suivante pour installer Laravel Passport et générer les clés de chiffrement :

```bash
php artisan passport:install
```