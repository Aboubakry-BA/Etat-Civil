# État Civil

Ce projet est une application web de gestion des extraits d'état civil. Ce document fournit des instructions d'installation et d'exécution pour les systèmes d'exploitation Windows, Ubuntu et macOS.

## Prérequis

- **Système d'exploitation** : Windows, Ubuntu ou macOS
- **Serveur web** : XAMPP, WAMP, LAMP, MAMP (dépendamment du système d'exploitation)
- **Outil de gestion de bases de données** : phpMyAdmin
- **Serveur de MailHog** : Inclut un exécutable dans le dossier du projet

## Installation sur Windows

1. **Cloner le dépôt :**

   ```sh
   git clone https://github.com/Aboubakry-BA/Etat-Civil
   cd Etat-Civil
   ```

2. **Installer et configurer XAMPP ou WAMP :**
   - Télécharger et installer [XAMPP](https://www.apachefriends.org/index.html) ou [WAMP](http://www.wampserver.com/).
   - Ouvrir le panneau de contrôle et démarrer Apache et MySQL.

3. **Importer la base de données :**
   - Ouvrir phpMyAdmin (généralement accessible via http://localhost/phpmyadmin).
   - Créer une nouvelle base de données nommée `extrait`.
   - Aller à l'onglet Importer.
   - Sélectionner le fichier `.sql` du dossier du projet.
   - Cliquer sur Exécuter.

4. **Activer le serveur de MailHog :**
   - Localiser le fichier `MailHog.exe` dans le dossier du projet.
   - Exécuter `MailHog.exe`.
   - Accéder à MailHog via http://localhost:8025.

5. **Configurer et lancer l'application :**
   - Ouvrir le fichier de configuration (`config.php` ou similaire) et ajuster les paramètres nécessaires.
   - Accéder à l'application via http://localhost/Etat-Civil dans un navigateur.

## Installation sur Ubuntu

1. **Cloner le dépôt :**

   ```sh
   git clone https://github.com/Aboubakry-BA/Etat-Civil
   cd Etat-Civil
   ```

2. **Installer et configurer LAMP :**

   ```sh
   sudo apt update
   sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql
   sudo systemctl start apache2
   sudo systemctl start mysql
   ```

3. **Importer la base de données :**
   - Installer phpMyAdmin si nécessaire :
   
     ```sh
     sudo apt install phpmyadmin
     ```
   - Accéder à phpMyAdmin via http://localhost/phpmyadmin.
   - Créer une nouvelle base de données nommée `extrait`.
   - Aller à l'onglet Importer.
   - Sélectionner le fichier `.sql` du dossier du projet.
   - Cliquer sur Exécuter.

4. **Activer le serveur de MailHog :**
   - Localiser le fichier MailHog dans le dossier du projet.
   - Rendre le fichier exécutable et le lancer :
   
     ```sh
     chmod +x MailHog
     ./MailHog
     ```
   - Accéder à MailHog via http://localhost:1025.

5. **Configurer et lancer l'application :**
   - Ouvrir le fichier de configuration (`config.php` ou similaire) et ajuster les paramètres nécessaires.
   - Accéder à l'application via http://localhost/Etat-Civil dans un navigateur.

## Installation sur macOS

1. **Cloner le dépôt :**

   ```sh
   git clone https://github.com/Aboubakry-BA/Etat-Civil
   cd Etat-Civil
   ```

2. **Installer et configurer MAMP :**
   - Télécharger et installer [MAMP](https://www.mamp.info/en/).
   - Ouvrir l'application MAMP et démarrer les serveurs Apache et MySQL.

3. **Importer la base de données :**
   - Accéder à phpMyAdmin via http://localhost/phpmyadmin.
   - Créer une nouvelle base de données nommée `extrait`.
   - Aller à l'onglet Importer.
   - Sélectionner le fichier `.sql` du dossier du projet.
   - Cliquer sur Exécuter.

4. **Activer le serveur de MailHog :**
   - Localiser le fichier MailHog dans le dossier du projet.
   - Rendre le fichier exécutable et le lancer :
   
     ```sh
     chmod +x MailHog
     ./MailHog
     ```
   - Accéder à MailHog via http://localhost:8025.

5. **Configurer et lancer l'application :**
   - Ouvrir le fichier de configuration (`config.php` ou similaire) et ajuster les paramètres nécessaires.
   - Accéder à l'application via http://localhost/Etat-Civil dans un navigateur.

## Remarques supplémentaires

- **Ports** : Assurez-vous que les ports utilisés par Apache, MySQL et MailHog ne sont pas bloqués par un pare-feu.
- **Compatibilité** : MailHog inclus dans le dossier du projet devrait fonctionner sur tous les systèmes d'exploitation pris en charge.
- **Configuration spécifique** : Pour des configurations supplémentaires, référez-vous à la documentation incluse dans le projet ou contactez l'équipe de développement.

En suivant ces instructions, vous devriez pouvoir installer et exécuter correctement l'application. Pour toute question ou problème, veuillez contacter l'équipe de développement.