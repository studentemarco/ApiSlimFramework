#!/bin/bash

# ============================================
# VERIFICA INSTALLAZIONE ApiSlimFramework
# ============================================

echo "╔════════════════════════════════════════════╗"
echo "║   Verifica Installazione ApiSlimFramework  ║"
echo "╚════════════════════════════════════════════╝"
echo ""

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ERRORS=0

# Funzione per check
check() {
    if [ $? -eq 0 ]; then
        echo "✅ $1"
    else
        echo "❌ $1"
        ((ERRORS++))
    fi
}

# Verifica file configurazione
echo "📋 Verifica file..."
[ -f "$PROJECT_DIR/chiavi.sh" ]
check "File chiavi.sh esiste"

[ -f "$PROJECT_DIR/composer.json" ]
check "File composer.json esiste"

[ -f "$PROJECT_DIR/public/index.php" ]
check "File public/index.php esiste"

[ -f "$PROJECT_DIR/src/Database.php" ]
check "File src/Database.php esiste"

echo ""
echo "🔧 Verifica software..."

# Verifica PHP
command -v php > /dev/null 2>&1
check "PHP installato"

if command -v php > /dev/null 2>&1; then
    php -m | grep -q pdo_mysql
    check "Estensione PHP pdo_mysql"
fi

# Verifica MariaDB/MySQL
command -v mariadb > /dev/null 2>&1 || command -v mysql > /dev/null 2>&1
check "MariaDB/MySQL installato"

# Verifica Composer
command -v composer > /dev/null 2>&1
check "Composer installato"

# Verifica vendor
[ -d "$PROJECT_DIR/vendor" ]
check "Dipendenze Composer installate"

echo ""
echo "🗄️  Verifica database..."

if [ -f "$PROJECT_DIR/chiavi.sh" ]; then
    source "$PROJECT_DIR/chiavi.sh"
    
    # Test connessione database
    if command -v mariadb > /dev/null 2>&1; then
        mariadb -u"$DB_USER" -p"$DB_PASS" -e "USE $DB_NAME; SHOW TABLES;" > /dev/null 2>&1
        check "Connessione database e tabelle"
    fi
fi

echo ""
echo "🌐 Verifica servizi..."

# Verifica MariaDB in esecuzione
sudo service mariadb status > /dev/null 2>&1
check "Servizio MariaDB attivo"

echo ""
echo "════════════════════════════════════════════"
if [ $ERRORS -eq 0 ]; then
    echo "✅ Installazione verificata con successo!"
    echo ""
    echo "🚀 Puoi avviare l'API con: ./avviaAPI.sh"
else
    echo "⚠️  Trovati $ERRORS problemi"
    echo ""
    echo "Possibili soluzioni:"
    echo "  - Esegui: ./setup.sh"
    echo "  - Esegui: ./install.sh"
    echo "  - Consulta: README.md"
fi
echo "════════════════════════════════════════════"
