#!/bin/bash

# ============================================
# AVVIO API ApiSlimFramework
# ============================================

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
CHIAVI_FILE="$PROJECT_DIR/chiavi.sh"

# Carica le configurazioni se esistono
if [ -f "$CHIAVI_FILE" ]; then
    source "$CHIAVI_FILE"
fi

# Usa la porta configurata o default 8000
PORT=${API_PORT:-8000}

echo "🚀 Avvio server API su porta $PORT..."
echo "📡 L'API sarà disponibile su: http://localhost:$PORT"
echo "🛑 Premi Ctrl+C per fermare il server"
echo ""

# Esporta le variabili d'ambiente per PHP
export DB_USER DB_PASS DB_NAME DB_HOST

/usr/bin/php -S 0.0.0.0:$PORT -t public