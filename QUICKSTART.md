# 🚀 Guida Rapida - ApiSlimFramework

## Installazione in 3 Passi (senza chmod!)

### 1️⃣ Clone

```bash
git clone <URL_TUO_REPOSITORY>
cd ApiSlimFramework
```

### 2️⃣ Bootstrap e Setup

```bash
bash bootstrap.sh
```

> **Perché `bash bootstrap.sh`?** Non richiede permessi di esecuzione! Lo script bootstrap imposterà automaticamente i permessi e avvierà il setup.

Ti verrà chiesto:
- Username database (default: `root`)
- Password database
- Username phpMyAdmin (default: `admin`)  
- Password phpMyAdmin

Il resto viene generato automaticamente!

### 3️⃣ Installazione

```bash
./install.sh
```

Aspetta qualche minuto mentre il sistema installa tutto.

## ✅ Test

```bash
# Avvia l'API
./avviaAPI.sh

# In un altro terminale, testa
curl http://localhost:8000/
```

Se vedi la lista degli endpoint, **funziona tutto!** 🎉

## 📚 Cosa Fare Dopo

- Leggi il [README.md](README.md) completo per tutti i dettagli
- Accedi a phpMyAdmin: http://localhost/phpmyadmin
- Testa gli endpoint API: http://localhost:8000/1, /2, /3, etc.

## ❓ Problemi?

Consulta la sezione [Troubleshooting](README.md#troubleshooting) nel README principale.

## 🔒 Sicurezza

⚠️ **IMPORTANTE**: Il file `chiavi.sh` contiene le tue password! 
- È già protetto e ignorato da git
- Non condividerlo mai
- Non committarlo su repository pubblici
