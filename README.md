# ApiSlimFramework

API REST basata su Slim Framework 4 per la gestione di un sistema Fornitori-Pezzi-Catalogo.

> **🚀 Installazione Rapida**: `git clone <URL>` → `cd ApiSlimFramework` → `bash bootstrap.sh` → `./install.sh`  
> **📚 Guida Rapida**: [QUICKSTART.md](QUICKSTART.md) | **📖 Guida Dettagliata**: [INSTALLAZIONE.md](INSTALLAZIONE.md)

## 📋 Indice

- [Descrizione](#descrizione)
- [Requisiti](#requisiti)
- [Installazione Rapida](#installazione-rapida)
- [Configurazione](#configurazione)
- [Avvio dell'API](#avvio-dellapi)
- [Struttura del Progetto](#struttura-del-progetto)
- [Endpoints API](#endpoints-api)
- [Database](#database)
- [Script Disponibili](#script-disponibili)
- [Troubleshooting](#troubleshooting)

## 📖 Descrizione

ApiSlimFramework è un'API REST che espone endpoint per gestire e interrogare un database relazionale contenente informazioni su:
- **Fornitori**: aziende che forniscono componenti
- **Pezzi**: componenti con caratteristiche (nome, colore, peso, città)
- **Catalogo**: relazione tra fornitori e pezzi con i relativi costi

L'API implementa varie query complesse per analizzare le relazioni tra fornitori e pezzi.

## 🔧 Requisiti

### Sistema Operativo
- Linux (testato su Ubuntu 24.04)
- Windows con WSL2
- macOS

### Software Richiesto
- **PHP** >= 8.0 con estensioni `php-mysql` e `libapache2-mod-php`
- **MariaDB** >= 10.11 (o MySQL >= 8.0)
- **Apache2** (installato automaticamente)
- **Composer** (installato automaticamente)

## 📥 Installazione Rapida

### Setup Completo in 3 Passi

```bash
# 1. Clone del repository
git clone <URL_TUO_REPOSITORY>
cd ApiSlimFramework

# 2. Configurazione guidata (ti chiederà solo le credenziali necessarie)
bash bootstrap.sh

# 3. Installazione automatica di tutto
./install.sh
```

> **💡 Nota**: Usiamo `bash bootstrap.sh` (senza `./`) perché non richiede permessi di esecuzione. Lo script bootstrap imposterà automaticamente i permessi per tutti gli altri script!

**Fatto! 🎉** L'installazione è completata.

### Cosa fa il setup automatico?

**`setup.sh`** - Configurazione guidata:
- Ti chiede username e password per il database
- Ti chiede username e password per phpMyAdmin  
- Genera automaticamente le chiavi di sicurezza
- Crea il file di configurazione `chiavi.sh`
- Aggiorna automaticamente il codice con le tue credenziali

**`install.sh`** - Installazione completa:
- Aggiorna il sistema
- Installa Apache2, PHP, MariaDB
- Installa e configura phpMyAdmin
- Installa Composer e le dipendenze PHP
- Importa il database

## ⚙️ Configurazione

Se hai usato `./setup.sh`, **non devi configurare nulla manualmente**! 

Il sistema usa variabili d'ambiente caricate automaticamente dal file `chiavi.sh`.

### Configurazione Manuale (Opzionale)

Se preferisci configurare manualmente:

1. Copia il file template:
```bash
cp chiavi.sh.example chiavi.sh
```

2. Modifica `chiavi.sh` con le tue credenziali:
```bash
nano chiavi.sh
```

3. Imposta i permessi corretti:
```bash
chmod 600 chiavi.sh
```

### Variabili Disponibili

Il file `chiavi.sh` contiene:

| Variabile | Descrizione | Default |
|-----------|-------------|---------|
| `DB_USER` | Utente database | root |
| `DB_PASS` | Password database | - |
| `DB_NAME` | Nome database | ApiSlimFramework |
| `DB_HOST` | Host database | localhost |
| `PMA_USER` | Utente phpMyAdmin | admin |
| `PMA_PASS` | Password phpMyAdmin | - |
| `BLOWFISH_SECRET` | Chiave sicurezza phpMyAdmin | auto-generata |
| `API_PORT` | Porta server API | 8000 |

## 🚀 Avvio dell'API

### Avvio Rapido

```bash
./avviaAPI.sh
```

L'API sarà disponibile su: **http://localhost:8000** (o sulla porta configurata)

### Avvio Manuale

```bash
# Carica le configurazioni
source chiavi.sh

# Avvia MariaDB
sudo service mariadb start

# Avvia il server API
php -S 0.0.0.0:${API_PORT:-8000} -t public
```

### Test dell'API

```bash
# Verifica che l'API risponda
curl http://localhost:8000/

# Test di un endpoint
curl http://localhost:8000/1
```

## 📁 Struttura del Progetto

```
ApiSlimFramework/
├── public/                 # Document root
│   └── index.php          # Entry point dell'API
├── src/                   # Codice sorgente
│   └── Database.php       # Gestione connessione DB
├── frontend/              # Interfacce web
│   ├── admin.php          # Pannello amministrazione
│   ├── catalogoFornitore.php
│   └── index.php
├── vendor/                # Dipendenze Composer
├── composer.json          # Configurazione dipendenze
├── ApiSlimFramework.sql   # Schema e dati DB
│
├── bootstrap.sh          # 🚀 INIZIA DA QUI! Imposta permessi e avvia setup
├── setup.sh              # ⭐ Setup guidato configurazione
├── install.sh            # ⭐ Installazione completa
├── avviaAPI.sh           # ⭐ Avvio server API
├── verifica.sh           # ⭐ Verifica installazione
├── start.sh              # Avvio servizi
│
├── chiavi.sh.example     # Template configurazione
├── chiavi.sh             # Configurazione (auto-generato, ignorato da git)
├── .gitignore            # File da ignorare
│
├── README.md             # Documentazione completa
├── QUICKSTART.md         # Guida rapida
└── INSTALLAZIONE.md      # Guida dettagliata installazione
```

## 🌐 Endpoints API

### Endpoint Principale

**GET** `/`

Restituisce la lista completa degli endpoint disponibili con descrizioni.

**Risposta esempio:**
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

### Endpoints Query

| Endpoint | Metodo | Descrizione |
|----------|--------|-------------|
| `/0` | GET | Lista delle tabelle nel database |
| `/1` | GET | Pezzi con almeno un fornitore |
| `/2` | GET | Fornitori che forniscono ogni pezzo |
| `/3` | GET | Fornitori che forniscono tutti i pezzi rossi |
| `/4` | GET | Pezzi forniti solo da Acme |
| `/5` | GET | Fornitori che ricaricano sopra media |
| `/6` | GET | Fornitore con ricarico massimo per ogni pezzo |
| `/7` | GET | Query 7 (personalizzata) |
| `/8` | GET | Query 8 (personalizzata) |
| `/9` | GET | Query 9 (personalizzata) |
| `/10` | GET | Query 10 (personalizzata) |

### Esempi di Utilizzo

```bash
# Lista tutti gli endpoint
curl http://localhost:8000/

# Pezzi con almeno un fornitore
curl http://localhost:8000/1

# Fornitori che forniscono tutti i pezzi rossi
curl http://localhost:8000/3

# Con jq per output formattato
curl -s http://localhost:8000/2 | jq .
```

### Risposta JSON

Tutte le risposte sono in formato JSON:

```json
[
  {
    "Pid": 1,
    "Pnome": "Vite",
    "Fid": 2,
    "Fnome": "Acme"
  }
]
```

In caso di errore:

```json
{
  "error": "Database error: ..."
}
```

## 🗄️ Database

### Schema del Database

Il database `ApiSlimFramework` contiene 3 tabelle principali e 10 viste.

#### Tabella `Fornitori`
```sql
CREATE TABLE Fornitori (
  fid INT PRIMARY KEY,
  fnome VARCHAR(256),
  indirizzo VARCHAR(256),
  citta VARCHAR(256)
);
```

#### Tabella `Pezzi`
```sql
CREATE TABLE Pezzi (
  pid INT PRIMARY KEY,
  pnome VARCHAR(256),
  colore VARCHAR(256),
  peso INT,
  citta VARCHAR(256)
);
```

#### Tabella `Catalogo`
```sql
CREATE TABLE Catalogo (
  fid INT,
  pid INT,
  costo INT,
  PRIMARY KEY (fid, pid),
  FOREIGN KEY (fid) REFERENCES Fornitori(fid),
  FOREIGN KEY (pid) REFERENCES Pezzi(pid)
);
```

### Viste Preconfigurate

Il database include 10 viste SQL (denominate `1` - `10`) che implementano query complesse per l'analisi dei dati. Gli endpoint API corrispondono a queste viste.

### Accesso phpMyAdmin

Dopo l'installazione, accedi a phpMyAdmin:

- **URL**: http://localhost/phpmyadmin
- **Username**: Quello configurato in `chiavi.sh` (variabile `PMA_USER`)
- **Password**: Quella configurata in `chiavi.sh` (variabile `PMA_PASS`)

## 📜 Script Disponibili

| Script | Comando | Descrizione |
|--------|---------|-------------|
| **Setup** | `./setup.sh` | ⭐ Configurazione guidata interattiva - **INIZIA DA QUI** |
| **Installazione** | `./install.sh` | Installazione completa di tutto l'ambiente |
| **Avvio API** | `./avviaAPI.sh` | Avvia il server di sviluppo (usa porta in chiavi.sh) |
| **Verifica** | `./verifica.sh` | Verifica che tutto sia installato correttamente |
| **Avvio Servizi** | `./start.sh` | Avvia Apache2 e MariaDB |
| Altri | `./altricomandi.sh` | Comandi di utilità addizionali |
| Pubblicazione | `./publish.sh` | Script per deploy/pubblicazione |

### Workflow Tipico

bash bootstrap.sh  # Imposta permessi e configura credenziali
./install.sh       # Installa tutto (automatico)
./verifica.sh      # Verifica installazione

# Uso quotidiano
./avviaAPI.sh      # Avvia l'API quando serve
```

> **💡 Pro Tip**: Dopo il primo `bash bootstrap.sh`, puoi usare `./` per tutti gli altri script perché i permessi saranno già impostati!so quotidiano
./avviaAPI.sh   # Avvia l'API quando serve
```

## 🔐 Sicurezza

### Protezione Credenziali

✅ Il file `chiavi.sh` è automaticamente **escluso da git** tramite `.gitignore`

✅ Le credenziali sono caricate come **variabili d'ambiente**, mai hardcoded

✅ Il file `chiavi.sh` ha **permessi 600** (solo proprietario)

### Best Practices

1. **Mai committare credenziali**
   ```bash
   # Verifica che chiavi.sh sia ignorato
   git status  # non deve apparire chiavi.sh
   ```

2. **Password sicure**
   - Usa password complesse (lettere, numeri, simboli)
   - Minimo 12 caratteri

3. **CORS in Produzione**
   
   Modifica [public/index.php](public/index.php) per limitare le origini:
   ```php
   ->withHeader('Access-Control-Allow-Origin', 'https://tuodominio.com')
   ```

4. **HTTPS in Produzione**
   
   Configura un certificato SSL/TLS (es. con Let's Encrypt)

5. **Validazione Input**
   
   Gli endpoint attuali usano viste SQL predefinite. Per endpoint con parametri, implementa validazione sanitizzazione.

## 🐛 Troubleshooting

### Problema: Permission denied

**Errore**: `bash: ./install.sh: Permission denied`

**Soluzione 1 (Consigliata)**:
```bash
bash bootstrap.sh  # Imposta permessi automaticamente
```

**Soluzione 2 (Manuale)**:
```bash
chmod +x *.sh
./setup.sh
```

**Soluzione 3 (Alternativa)**:
```bash
bash setup.sh  # Funziona senza permessi di esecuzione
bash install.sh
bash avviaAPI.sh
```

### Problema: File configurazione mancante

**Errore**: `❌ File configurazione mancante: chiavi.sh`

**Soluzione**:
```bash
bash setup.sh  # oppure ./setup.sh se hai i permessi
```

### Problema: Errore connessione database

**Errore**: `Connection refused` o `Access denied`

**Soluzioni**:
```bash
# 1. Verifica che MariaDB sia avviato
sudo service mariadb status
sudo service mariadb start

# 2. Verifica le credenziali in chiavi.sh
cat chiavi.sh

# 3. Testa la connessione manualmente
mysql -u $DB_USER -p$DB_PASS -e "SHOW DATABASES;"
```

### Problema: Porta già in uso

**Errore**: `Address already in use`

**Soluzioni**:
```bash
# Opzione 1: Cambia porta in chiavi.sh
nano chiavi.sh
# Modifica API_PORT="8001"

# Opzione 2: Trova e termina il processo sulla porta
sudo lsof -i :8000
sudo kill <PID>
```

### Problema: Dipendenze mancanti

**Errore**: `Class 'Slim\Factory\AppFactory' not found`

**Soluzione**:
```bash
# Reinstalla le dipendenze
composer install
```

### Problema: Errori di permessi

**Soluzione**:
```bash
# Ripristina permessi corretti
chmod +x setup.sh install.sh avviaAPI.sh start.sh
chmod 600 chiavi.sh
```

### Debug Generale

```bash
# Controlla versione PHP
php -v

# Controlla estensioni PHP
php -m | grep -i mysql

# Controlla stato servizi
sudo service mariadb status
sudo service apache2 status

# Log MariaDB
sudo tail -f /var/log/mysql/error.log

# Test endpoint API
curl -v http://localhost:8000/
```

## 📝 Licenza

Questo progetto è rilasciato come open source. Specificare la licenza appropriata per il tuo uso.

## 🤝 Contributi

I contributi sono benvenuti! 

1. Fai un fork del progetto
2. Crea un branch per la tua feature (`git checkout -b feature/NuovaFeature`)
3. Commit delle modifiche (`git commit -m 'Aggiunge NuovaFeature'`)
4. Push al branch (`git push origin feature/NuovaFeature`)
5. Apri una Pull Request

## 📞 Supporto

Per problemi o domande:
- Apri una [Issue](../../issues)
- Consulta la sezione [Troubleshooting](#troubleshooting)

---

**Nota**: Questo progetto è a scopo didattico/dimostrativo. Per l'uso in produzione, implementare:
- Autenticazione/Autorizzazione (JWT, OAuth)
- Rate limiting
- Logging strutturato
- Monitoraggio
- Backup automatici
- Test automatizzati