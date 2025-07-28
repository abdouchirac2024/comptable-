<?php

echo "=== Configuration Laravel pour php artisan serve ===\n\n";

// 1. Vérifier si le fichier .env existe
if (!file_exists('.env')) {
    echo "📝 Création du fichier .env...\n";
    
    $envContent = "APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=comptable
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=yohmarley237@gmail.com
MAIL_PASSWORD=aqivoxnzowsgfvks
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=yohmarley237@gmail.com
MAIL_FROM_NAME=\"Laravel\"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME=\"Laravel\"
VITE_PUSHER_APP_KEY=\"\"
VITE_PUSHER_HOST=\"\"
VITE_PUSHER_PORT=\"\"
VITE_PUSHER_SCHEME=\"\"
VITE_PUSHER_APP_CLUSTER=\"\"";

    file_put_contents('.env', $envContent);
    echo "✅ Fichier .env créé\n";
} else {
    echo "✅ Fichier .env existe déjà\n";
}

// 2. Générer la clé d'application
echo "\n🔑 Génération de la clé d'application...\n";
$output = shell_exec('php artisan key:generate --force 2>&1');
echo $output;

// 3. Nettoyer le cache
echo "\n🧹 Nettoyage du cache...\n";
$output = shell_exec('php artisan config:clear 2>&1');
echo $output;

$output = shell_exec('php artisan cache:clear 2>&1');
echo $output;

$output = shell_exec('php artisan route:clear 2>&1');
echo $output;

$output = shell_exec('php artisan view:clear 2>&1');
echo $output;

// 4. Vérifier les routes
echo "\n🔍 Vérification des routes...\n";
$output = shell_exec('php artisan route:list --path=api/formations 2>&1');
echo $output;

// 5. Instructions pour démarrer le serveur
echo "\n🚀 Instructions pour démarrer le serveur:\n";
echo "1. Ouvrez un terminal dans le dossier du projet\n";
echo "2. Exécutez: php artisan serve --host=127.0.0.1 --port=8000\n";
echo "3. Le serveur sera accessible sur: http://127.0.0.1:8000\n";
echo "4. Testez l'API avec Postman:\n";
echo "   - PUT http://127.0.0.1:8000/api/formations/1\n";
echo "   - Content-Type: multipart/form-data\n";
echo "   - Body: nom, description, duree, tarif, slug, image\n";

echo "\n✅ Configuration terminée!\n"; 