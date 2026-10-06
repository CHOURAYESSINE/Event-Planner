# Déploiement Vercel

Ce projet utilise le runtime communautaire vercel-php 0.7.4 (PHP 8.3).

1. Publier les fichiers api/index.php, vercel.json, .vercelignore et ce guide sur GitHub.
2. Dans Vercel, importer CHOURAYESSINE/Event-Planner. Framework : Other. Node.js : 22.x. Le fichier vercel.json configure la compilation et le dossier public.
3. Ajouter les variables de .env.vercel.example dans Settings > Environment Variables. APP_KEY doit être une clé de production distincte, générée avec php artisan key:generate --show. Ne jamais publier la clé ni le fichier .env.
4. Connecter une base PostgreSQL ou MySQL persistante. Pour MySQL : DB_CONNECTION=mysql, DB_PORT=3306 et les paramètres TLS du fournisseur.
5. Initialiser cette base depuis un environnement de confiance : php artisan migrate --force. Configurer les variables de connexion de la base hébergée avant cette commande. Ne pas exécuter les migrations automatiquement lors de chaque requête.
6. Créer un administrateur avec un mot de passe personnel. Ne pas utiliser AdminUserSeeder en production : son mot de passe est admin.
7. Déployer puis vérifier /, /login, /register et le tableau de bord.

Le fichier SQLite local est exclu du déploiement. Les événements et utilisateurs locaux ne sont pas transférés.

Limite actuelle : les images téléchargées par l'administration utilisent le disque public local. Sur Vercel, il faut adapter ces téléchargements à un stockage externe (S3 ou équivalent) avant d'utiliser cette fonction. Les images déjà présentes dans public/images restent disponibles.

La préparation locale ne confirme pas un déploiement : il faut un compte Vercel connecté, les variables et une base hébergée.
