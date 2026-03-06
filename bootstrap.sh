#!/bin/bash

# ============================================
# BOOTSTRAP ApiSlimFramework
# ============================================
# Questo script imposta i permessi e avvia il setup
# Usalo così: bash bootstrap.sh
# (non serve chmod!)

echo "╔════════════════════════════════════════════╗"
echo "║   Bootstrap ApiSlimFramework              ║"
echo "╚════════════════════════════════════════════╝"
echo ""

echo "🔧 Impostazione permessi di esecuzione..."

# Imposta i permessi di esecuzione su tutti gli script
chmod +x setup.sh
chmod +x install.sh
chmod +x avviaAPI.sh
chmod +x start.sh
chmod +x verifica.sh
chmod +x publish.sh
chmod +x altricomandi.sh

echo "✅ Permessi impostati!"
echo ""
echo "🚀 Avvio configurazione guidata..."
echo ""

# Esegui lo script di setup
./setup.sh
