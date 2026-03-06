#!/bin/bash

# Directory del progetto
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
WWW_ROOT="/var/www/html"

if [ "$1" == "--list" ]; then
  echo "Elenco delle rotte disponibili:"
  echo
  # Mostra solo i link simbolici con il loro target
  find "$WWW_ROOT" -maxdepth 1 -type l -exec ls -l {} \; | awk '{print $9 " -> " $11}'
  exit 0
fi

# elimina un collegamento
if [ "$1" == "--remove" ]; then
  if [ -z "$2" ]; then
    echo "Uso: $0 --remove <nome_link>"
    exit 1
  fi
  LINK_TO_REMOVE="$WWW_ROOT/$2"
  if [ -L "$LINK_TO_REMOVE" ]; then
    rm "$LINK_TO_REMOVE"
    echo "Rimosso link simbolico: $2"
  else
    echo "Il link simbolico $2 non esiste."
  fi
  exit 0
fi

# Controllo argomenti
if [ "$#" -ne 2 ]; then
  echo "Uso: $0 <nome_link> <cartella_riferimento>"
  echo "Oppure: $0 --list"
  exit 1
fi

LINK_NAME="$1"
TARGET_DIR="$2"

ln -s "$PROJECT_DIR/$TARGET_DIR" "$WWW_ROOT/$LINK_NAME"
echo "Creato link simbolico: $LINK_NAME -> $TARGET_DIR"
echo "Path completo: $PROJECT_DIR/$TARGET_DIR"