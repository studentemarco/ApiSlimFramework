#!/bin/bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

if [[ ! -f "$ROOT_DIR/install.sh" ]]; then
  echo "Errore: install.sh non trovato in $ROOT_DIR"
  exit 1
fi

if [[ ! -f "$ROOT_DIR/altricomandi.sh" ]]; then
  echo "Errore: altricomandi.sh non trovato in $ROOT_DIR"
  exit 1
fi

echo "[1/2] Eseguo install.sh"
bash "$ROOT_DIR/install.sh"

echo "[2/2] Eseguo altricomandi.sh"
bash "$ROOT_DIR/altricomandi.sh"

echo "Setup completato con successo."
