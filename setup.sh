#!/bin/bash

# ============================================
# SETUP INIZIALE ApiSlimFramework
# ============================================

set -e

echo "╔════════════════════════════════════════════╗"
echo "║   Setup ApiSlimFramework - Configurazione  ║"
echo "╚════════════════════════════════════════════╝"
echo ""

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
CHIAVI_FILE="$PROJECT_DIR/chiavi.sh"
EXAMPLE_FILE="$PROJECT_DIR/chiavi.sh.example"

# Controlla se chiavi.sh esiste già
if [ -f "$CHIAVI_FILE" ]; then
    echo "⚠️  Il file chiavi.sh esiste già!"
    read -p "Vuoi sovrascriverlo? (s/N): " overwrite
    if [[ ! "$overwrite" =~ ^[sS]$ ]]; then
        echo "Setup annullato. Usa il file esistente o cancellalo manualmente."
        exit 0
    fi
fi

echo "📝 Configurazione guidata delle credenziali"
echo ""

# Database user
read -p "Utente database [root]: " db_user
db_user=${db_user:-root}

# Database password
read -sp "Password database: " db_pass
echo ""

# Database name
read -p "Nome database [ApiSlimFramework]: " db_name
db_name=${db_name:-ApiSlimFramework}

# PhpMyAdmin user
read -p "Utente phpMyAdmin [admin]: " pma_user
pma_user=${pma_user:-admin}

# PhpMyAdmin password
read -sp "Password phpMyAdmin: " pma_pass
echo ""

# Genera Blowfish Secret automaticamente
echo ""
echo "🔐 Generazione Blowfish Secret..."
if command -v openssl &> /dev/null; then
    blowfish_secret=$(openssl rand -base64 32 | head -c 32)
else
    blowfish_secret=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
fi

# Porta API
read -p "Porta per l'API [8000]: " api_port
api_port=${api_port:-8000}

# Crea il file chiavi.sh
cat > "$CHIAVI_FILE" <<EOF
#!/bin/bash

# ============================================
# CONFIGURAZIONE ApiSlimFramework
# ============================================
# File generato automaticamente da setup.sh

# --- CREDENZIALI DATABASE ---
export DB_USER="$db_user"
export DB_PASS="$db_pass"
export DB_NAME="$db_name"
export DB_HOST="localhost"

# --- CREDENZIALI PHPMYADMIN ---
export PMA_USER="$pma_user"
export PMA_PASS="$pma_pass"

# --- BLOWFISH SECRET ---
export BLOWFISH_SECRET="$blowfish_secret"

# --- CONFIGURAZIONE API ---
export API_PORT="$api_port"
EOF

chmod 600 "$CHIAVI_FILE"
chmod +x "$CHIAVI_FILE"

# Aggiorna src/Database.php con le credenziali
echo ""
echo "🔧 Aggiornamento file Database.php..."

cat > "$PROJECT_DIR/src/Database.php" <<'DBEOF'
<?php

namespace App;

use PDO;

class Database {
    public static function connect() {
        $host = getenv('DB_HOST') ?: 'localhost';
        $dbname = getenv('DB_NAME');
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        
        if (!$dbname) {
            throw new \Exception('DB_NAME non configurato! Esegui setup.sh per configurare il database.');
        }
        
        return new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
}
DBEOF

echo ""
echo "✅ Configurazione completata!"
echo ""
echo "📁 File creato: $CHIAVI_FILE"
echo "🔒 Permessi impostati: 600 (solo proprietario)"
echo ""
echo "🚀 Prossimi passi:"
echo "   1. Esegui: ./install.sh"
echo "   2. Avvia l'API: ./avviaAPI.sh"
echo ""
