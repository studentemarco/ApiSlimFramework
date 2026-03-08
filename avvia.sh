#!/bin/bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

if [[ ! -f "$ROOT_DIR/start.sh" ]]; then
  echo "Errore: start.sh non trovato in $ROOT_DIR"
  exit 1
fi

if [[ ! -f "$ROOT_DIR/avviaAPI.sh" ]]; then
  echo "Errore: avviaAPI.sh non trovato in $ROOT_DIR"
  exit 1
fi

echo "[1/2] Eseguo start.sh"
bash "$ROOT_DIR/start.sh"

echo "[2/2] Eseguo avviaAPI.sh"
bash "$ROOT_DIR/avviaAPI.sh"

echo "Avvio completato."
