#!/bin/bash

# ============================================
# INSTALLAZIONE ApiSlimFramework
# ============================================

set -e

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
CHIAVI_FILE="$PROJECT_DIR/chiavi.sh"

echo "╔════════════════════════════════════════════╗"
echo "║   Installazione ApiSlimFramework           ║"
echo "╚════════════════════════════════════════════╝"
echo ""

# Verifica che il file di configurazione esista
if [ ! -f "$CHIAVI_FILE" ]; then
    echo "❌ File configurazione mancante: $CHIAVI_FILE"
    echo ""
    echo "Prima di procedere, esegui: ./setup.sh"
    exit 1
fi

# Carica le variabili di ambiente
source "$CHIAVI_FILE"

# Verifica che tutte le variabili necessarie siano configurate
if [ -z "$PMA_USER" ] || [ -z "$PMA_PASS" ] || [ -z "$BLOWFISH_SECRET" ] || [ -z "$DB_USER" ] || [ -z "$DB_PASS" ]; then
    echo "❌ Configurazione incompleta in $CHIAVI_FILE"
    echo "Esegui nuovamente: ./setup.sh"
    exit 1
fi

# Rimuovi repository problematici
sudo rm -f /etc/apt/sources.list.d/yarn.list
sudo rm -f /etc/apt/sources.list.d/microsoft.list 2>/dev/null || true

echo "🛠️  Aggiornamento pacchetti e installazione Apache, PHP, MariaDB..."
sudo apt update
sudo apt install -y apache2 php libapache2-mod-php php-mysql mariadb-server wget unzip

echo "🚀 Avvio di MariaDB..."
sudo service mariadb start

echo "🔒 Esecuzione configurazione sicura MariaDB (automatica con expect)..."
sudo mariadb <<EOF
DELETE FROM mysql.user WHERE User='';
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';
FLUSH PRIVILEGES;
EOF

echo "📂 Installazione phpMyAdmin..."
cd /var/www/html
sudo wget https://www.phpmyadmin.net/downloads/phpMyAdmin-latest-all-languages.zip
sudo unzip phpMyAdmin-latest-all-languages.zip
sudo mv phpMyAdmin-*-all-languages phpmyadmin
sudo rm phpMyAdmin-latest-all-languages.zip

echo "⚙️  Configurazione phpMyAdmin..."
cd phpmyadmin
sudo cp config.sample.inc.php config.inc.php
sudo sed -i "s/\(\$cfg\['blowfish_secret'\] = \).*/\1'$BLOWFISH_SECRET';/" config.inc.php

echo "🌐 Configurazione Apache per phpMyAdmin..."
cat <<EOCONF | sudo tee /etc/apache2/conf-available/phpmyadmin.conf
Alias /phpmyadmin /var/www/html/phpmyadmin

<Directory /var/www/html/phpmyadmin>
    Options Indexes FollowSymLinks
    DirectoryIndex index.php
    AllowOverride All
    Require all granted
</Directory>
EOCONF


sudo service apache2 restart
sudo a2enconf phpmyadmin
sudo service apache2 restart

echo "👤 Creazione utente MariaDB per phpMyAdmin..."
sudo mariadb <<EOF
CREATE USER IF NOT EXISTS '$PMA_USER'@'localhost' IDENTIFIED BY '$PMA_PASS';
GRANT ALL PRIVILEGES ON *.* TO '$PMA_USER'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EOF

echo ""
echo "✅ Installazione completata!"
echo "🔗 Accedi a phpMyAdmin all'indirizzo:"
echo "    http://localhost/phpmyadmin"
echo ""
echo "👤 Credenziali di accesso:"
echo "    Utente: $PMA_USER"
echo "    Password: $PMA_PASS"

echo ""
echo "📦 Installazione dipendenze PHP..."
cd "$PROJECT_DIR" && composer require slim/slim:"^4"
composer require slim/psr7

echo ""
echo "💾 Creazione database..."
# Crea il database se non esiste
sudo mariadb -u root <<DBEOF
CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE \`$DB_NAME\`;
DBEOF

echo "💾 Importazione database..."
# Sostituisci il DEFINER e il commento del database nel file SQL
sed -e "s/DEFINER=\`[^@]*\`@\`[^']*\`/DEFINER=\`$PMA_USER\`@\`localhost\`/g" \
    -e "s/-- Database: \`ApiSlimFramework\`/-- Database: \`$DB_NAME\`/g" \
    "$PROJECT_DIR/ApiSlimFramework.sql" > "$PROJECT_DIR/ApiSlimFramework_temp.sql"

# Importa nel database selezionato
sudo mariadb -u root "$DB_NAME" < "$PROJECT_DIR/ApiSlimFramework_temp.sql"
rm "$PROJECT_DIR/ApiSlimFramework_temp.sql"

echo "✅ Database '$DB_NAME' importato con utente: $PMA_USER"
