# 📚 Guida Completa di Installazione

## Processo di Installazione Automatizzata

Questo progetto è stato progettato per essere **estremamente facile da installare**. Non devi modificare nessun file manualmente!

## 🎯 Obiettivo

Permetterti di installare e avviare l'API con **il minimo sforzo**, chiedendoti solo le credenziali necessarie.

## 📋 Processo Passo-Passo

### Passo 1: Clone del Repository

```bash
git clone <URL_TUO_REPOSITORY>
cd ApiSlimFramework
```

**Cosa succede**: Scarichi il codice sul tuo computer.

---

### Passo 2: Bootstrap e Setup Configurazione

```bash
bash bootstrap.sh
```

> **💡 Importante**: Usa `bash bootstrap.sh` (non `./bootstrap.sh`) perché non richiede permessi di esecuzione! Questo è il trucco per evitare il problema "Permission denied".

Lo script bootstrap:
1. ✅ Imposta automaticamente i permessi di esecuzione su tutti gli script
2. ✅ Avvia automaticamente `setup.sh`

**Cosa ti chiede**:
1. Username per il database (default: `root`)
2. Password per il database
3. Username per phpMyAdmin (default: `admin`)
4. Password per phpMyAdmin
5. Porta per l'API (default: `8000`)

**Cosa fa automaticamente**:
- ✅ Genera una chiave di sicurezza casuale (Blowfish Secret)
- ✅ Crea il file `chiavi.sh` con tutte le configurazioni
- ✅ Imposta i permessi corretti (600 - solo tu puoi leggerlo)
- ✅ Aggiorna `src/Database.php` per usare le variabili d'ambiente
- ✅ NON ti chiede di modificare nessun file!

**Output**:
```
✅ Configurazione completata!

📁 File creato: /path/to/chiavi.sh
🔒 Permessi impostati: 600 (solo proprietario)

🚀 Prossimi passi:
   1. Esegui: ./install.sh
   2. Avvia l'API: ./avviaAPI.sh
```

---

### Passo 3: Installazione Automatica

```bash
./install.sh
```

**Cosa fa automaticamente**:
1. ✅ Aggiorna il sistema operativo
2. ✅ Installa Apache2
3. ✅ Installa PHP e le estensioni necessarie
4. ✅ Installa MariaDB
5. ✅ Configura MariaDB in modo sicuro
6. ✅ Scarica e installa phpMyAdmin
7. ✅ Configura phpMyAdmin con le tue credenziali
8. ✅ Crea l'utente database
9. ✅ Installa Composer
10. ✅ Installa Slim Framework e dipendenze
11. ✅ Importa il database con tabelle e dati

**Durata**: 5-10 minuti (dipende dalla velocità della connessione)

**Output finale**:
```
✅ Installazione completata!
🔗 Accedi a phpMyAdmin all'indirizzo:
    http://localhost/phpmyadmin

👤 Credenziali di accesso:
    Utente: [tuo_utente]
    Password: [tua_password]
```

---

### Passo 4: Verifica Installazione (Opzionale)

```bash
./verifica.sh
```

**Cosa controlla**:
- ✅ Tutti i file necessari esistono
- ✅ PHP è installato con estensioni corrette
- ✅ MariaDB è installato e in esecuzione
- ✅ Composer è installato
- ✅ Dipendenze sono installate
- ✅ Database esiste e contiene le tabelle
- ✅ Connessione al database funziona

**Output**:
```
✅ Installazione verificata con successo!

🚀 Puoi avviare l'API con: ./avviaAPI.sh
```

---

### Passo 5: Avvio dell'API

```bash
./avviaAPI.sh
```

**Cosa fa**:
- ✅ Carica automaticamente le configurazioni da `chiavi.sh`
- ✅ Esporta le variabili d'ambiente per PHP
- ✅ Avvia il server di sviluppo PHP sulla porta configurata

**Output**:
```
🚀 Avvio server API su porta 8000...
📡 L'API sarà disponibile su: http://localhost:8000
🛑 Premi Ctrl+C per fermare il server
```

---

### Passo 6: Test dell'API

In un altro terminale:

```bash
# Test endpoint principale
curl http://localhost:8000/

# Test endpoint specifico
curl http://localhost:8000/1

# Con output formattato (richiede jq)
curl -s http://localhost:8000/2 | jq .
```

**Risposta attesa**:
```json
{
  "message": "",
  "endpoints": [
    {
      "method": "GET",
      "path": "/0",
      "description": "Lista delle tabelle"
    },
    ...
  ]
}
```

---

## 🔐 File Creati Automaticamente

### `chiavi.sh` (Generato da setup.sh)

```bash
#!/bin/bash

# --- CREDENZIALI DATABASE ---
export DB_USER="tuo_utente"
export DB_PASS="tua_password"
export DB_NAME="ApiSlimFramework"
export DB_HOST="localhost"

# --- CREDENZIALI PHPMYADMIN ---
export PMA_USER="admin"
export PMA_PASS="tua_password_pma"

# --- BLOWFISH SECRET ---
export BLOWFISH_SECRET="generata_automaticamente"

# --- CONFIGURAZIONE API ---
export API_PORT="8000"
```

**Sicurezza**:
- ✅ Permessi 600 (solo tu puoi leggerlo)
- ✅ Ignorato da git (non verrà committato)
- ✅ Usato solo dal tuo sistema

---

## ❓ FAQ - Domande Frequenti

### Devo modificare qualche file di codice?

**NO!** Tutto viene configurato automaticamente da `setup.sh`.

### Dove sono salvate le mie password?

Nel file `chiavi.sh` nella directory del progetto. Il file ha permessi 600 (solo tu puoi leggerlo) ed è ignorato da git.

### Posso cambiare le credenziali dopo?

Sì! Esegui di nuovo `./setup.sh` e inserisci le nuove credenziali.

### E se voglio usare una porta diversa?

Durante `./setup.sh` ti viene chiesta la porta. Puoi anche modificare `API_PORT` in `chiavi.sh`.

### Come faccio a fermare l'API?

Premi `Ctrl+C` nel terminale dove hai eseguito `./avviaAPI.sh`.

### Posso usare questo in produzione?

Il setup attuale è per sviluppo/test. Per produzione, implementa:
- HTTPS con certificato SSL
- Autenticazione/Autorizzazione
- Rate limiting
- Firewall
- Backup automatici

---

## 🆘 Risoluzione Problemi

### Problema: "File configurazione mancante"

**Soluzione**: Esegui `./setup.sh`

### Problema: "Porta già in uso"

**Soluzione**: 
```bash
# Cambia porta in chiavi.sh
nano chiavi.sh
# Modifica API_PORT="8001"
```

### Problema: "Errore connessione database"

**Soluzione**:
```bash
# Verifica che MariaDB sia avviato
sudo service mariadb status
sudo service mariadb start

# Testa connessione
source chiavi.sh
mysql -u "$DB_USER" -p"$DB_PASS" -e "SHOW DATABASES;"
```

---

## 📞 Supporto

Se hai problemi non risolti:
1. Controlla il [README.md](README.md) completo
2. Esegui `./verifica.sh` per diagnostica
3. Apri una Issue nel repository

---

**🎉 Buon utilizzo dell'API!**
