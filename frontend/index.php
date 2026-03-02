<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Slim Framework - Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #ec4899;
            --success: #10b981;
            --danger: #ef4444;
            --dark: #1f2937;
            --light: #f9fafb;
            --border: #e5e7eb;
            --text: #374151;
            --sidebar-width: 340px;
            --sidebar-collapsed: 78px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--text);
            overflow: hidden;
        }

        .app-shell {
            display: grid;
            grid-template-columns: var(--sidebar-width) 1fr;
            height: 100vh;
            transition: grid-template-columns 0.3s ease;
            overflow: hidden;
        }

        .app-shell.sidebar-collapsed {
            grid-template-columns: var(--sidebar-collapsed) 1fr;
        }

        .sidebar {
            background: #ffffff;
            border-right: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            height: 100vh;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 16px;
            border-bottom: 1px solid var(--border);
            min-height: 70px;
            gap: 12px;
        }

        .sidebar-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
            white-space: nowrap;
        }

        .sidebar-subtitle {
            font-size: 0.8rem;
            opacity: 0.7;
            margin-top: 2px;
            white-space: nowrap;
        }

        .sidebar-toggle {
            border: none;
            background: var(--light);
            color: var(--dark);
            border-radius: 8px;
            width: 34px;
            height: 34px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .sidebar-toggle:hover {
            background: var(--primary);
            color: #ffffff;
        }

        .sidebar-list {
            list-style: none;
            padding: 10px;
            overflow-y: auto;
            flex: 1;
        }

        .query-item {
            width: 100%;
            text-align: left;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #ffffff;
            margin-bottom: 10px;
            padding: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .query-item:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-sm);
            transform: translateY(-1px);
        }

        .query-item.active {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.08);
        }

        .query-name {
            color: var(--dark);
            font-weight: 700;
            font-size: 0.92rem;
            margin-bottom: 6px;
        }

        .query-desc {
            font-size: 0.84rem;
            line-height: 1.35;
            color: var(--text);
            opacity: 0.9;
        }

        .query-path {
            display: inline-block;
            margin-top: 8px;
            font-size: 0.75rem;
            color: var(--primary);
            font-family: 'Courier New', monospace;
        }

        .app-shell.sidebar-collapsed .sidebar-title-group,
        .app-shell.sidebar-collapsed .query-desc,
        .app-shell.sidebar-collapsed .query-path {
            display: none;
        }

        .app-shell.sidebar-collapsed .query-item {
            padding: 10px;
        }

        .app-shell.sidebar-collapsed .query-name {
            margin-bottom: 0;
            font-size: 0.82rem;
            text-align: center;
        }

        .main-content {
            padding: 30px;
            overflow-y: auto;
        }

        .main-panel {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: var(--shadow);
            min-height: calc(100vh - 60px);
            padding: 28px;
            display: flex;
            flex-direction: column;
        }

        .main-header {
            margin-bottom: 18px;
        }

        .main-title {
            font-size: 1.7rem;
            color: var(--dark);
            margin-bottom: 6px;
        }

        .main-subtitle {
            font-size: 0.95rem;
            opacity: 0.75;
        }

        .result-meta {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .meta-item {
            background: var(--light);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 0.88rem;
        }

        .meta-label {
            font-weight: 700;
            color: var(--dark);
        }

        .meta-value {
            color: var(--primary);
            margin-left: 6px;
            font-weight: 600;
        }

        .loading {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 30px 10px;
            color: var(--text);
        }

        .spinner {
            border: 4px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            width: 34px;
            height: 34px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .success {
            background: #dcfce7;
            border-left: 4px solid var(--success);
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 14px;
        }

        .error {
            background: #fee2e2;
            border-left: 4px solid var(--danger);
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 14px;
        }

        .table-responsive {
            overflow-x: auto;
            border: 1px solid var(--border);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #ffffff;
            padding: 12px 14px;
            text-align: left;
            font-size: 0.9rem;
        }

        td {
            padding: 11px 14px;
            border-bottom: 1px solid var(--border);
            font-size: 0.9rem;
        }

        tr:hover {
            background: var(--light);
        }

        .json-display {
            background: var(--light);
            border-left: 4px solid var(--primary);
            padding: 14px;
            border-radius: 8px;
            max-height: 480px;
            overflow: auto;
        }

        .json-display pre {
            font-family: 'Courier New', monospace;
            font-size: 0.86rem;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .empty-state {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 30px;
            color: var(--text);
        }

        .empty-state h3 {
            color: var(--dark);
            margin-bottom: 8px;
        }

        @media (max-width: 920px) {
            .app-shell {
                grid-template-columns: 1fr;
                grid-template-rows: auto 1fr;
            }

            .app-shell.sidebar-collapsed {
                grid-template-columns: 1fr;
            }

            .sidebar {
                border-right: none;
                border-bottom: 1px solid var(--border);
                max-height: 320px;
                height: auto;
            }

            .app-shell.sidebar-collapsed .sidebar {
                max-height: 70px;
            }

            .main-content {
                padding: 16px;
            }

            .main-panel {
                min-height: calc(100vh - 370px);
            }
        }
    </style>
</head>
<body>
    <div id="appShell" class="app-shell">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title-group">
                    <div class="sidebar-title">🚀 API Dashboard</div>
                    <div class="sidebar-subtitle">Slim Framework - Query List</div>
                </div>
                <button id="toggleSidebar" class="sidebar-toggle" aria-label="Apri/chiudi sidebar">◀</button>
            </div>
            <ul id="queriesList" class="sidebar-list"></ul>
        </aside>

        <main class="main-content">
            <section class="main-panel">
                <div class="main-header">
                    <h1 class="main-title">Risultati Query</h1>
                    <p class="main-subtitle">Seleziona una query dalla sidebar per visualizzarne i risultati.</p>
                </div>
                <div id="resultsContainer" class="empty-state">
                    <div>
                        <h3>Nessuna query selezionata</h3>
                        <p>Apri la sidebar e scegli una query.</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        const API_BASE_URL = 'https://shiny-space-bassoon-r474j6rqqv9q3pgpq-8000.app.github.dev';
        const endpoints = [
            { id: 0, name: 'Query #0', method: 'GET', path: '/0', description: 'Lista delle tabelle' },
            { id: 1, name: 'Query #1', method: 'GET', path: '/1', description: 'Pezzi con almeno un fornitore' },
            { id: 2, name: 'Query #2', method: 'GET', path: '/2', description: 'Fornitori che forniscono ogni pezzo' },
            { id: 3, name: 'Query #3', method: 'GET', path: '/3', description: 'Fornitori che forniscono tutti i pezzi rossi' },
            { id: 4, name: 'Query #4', method: 'GET', path: '/4', description: 'Pezzi forniti solo da Acme' },
            { id: 5, name: 'Query #5', method: 'GET', path: '/5', description: 'Fornitori che ricaricano sopra media' },
            { id: 6, name: 'Query #6', method: 'GET', path: '/6', description: 'Fornitore con ricarico massimo per ogni pezzo' },
            { id: 7, name: 'Query #7', method: 'GET', path: '/7', description: 'Fornitori che forniscono solo pezzi rossi' },
            { id: 8, name: 'Query #8', method: 'GET', path: '/8', description: 'Fornitori che forniscono rosso e verde' },
            { id: 9, name: 'Query #9', method: 'GET', path: '/9', description: 'Fornitori che forniscono rosso o verde' },
            { id: 10, name: 'Query #10', method: 'GET', path: '/10', description: 'Pezzi forniti da almeno 2 fornitori' }
        ];

        let activeQueryId = null;

        function initializeSidebar() {
            const list = document.getElementById('queriesList');
            list.innerHTML = endpoints.map(endpoint => `
                <li>
                    <button class="query-item" data-id="${endpoint.id}">
                        <div class="query-name">${endpoint.name}</div>
                        <div class="query-desc">${endpoint.description}</div>
                        <div class="query-path">${endpoint.method} ${endpoint.path}</div>
                    </button>
                </li>
            `).join('');

            list.querySelectorAll('.query-item').forEach(button => {
                button.addEventListener('click', () => {
                    const id = Number(button.dataset.id);
                    selectQuery(id);
                });
            });
        }

        function selectQuery(id) {
            if (activeQueryId === id) {
                return;
            }

            activeQueryId = id;
            document.querySelectorAll('.query-item').forEach(item => {
                item.classList.toggle('active', Number(item.dataset.id) === id);
            });

            const endpoint = endpoints.find(e => e.id === id);
            if (endpoint) {
                callEndpoint(endpoint);
            }
        }

        async function callEndpoint(endpoint) {
            const container = document.getElementById('resultsContainer');
            container.className = '';
            container.innerHTML = `
                <div class="loading">
                    <div class="spinner"></div>
                    <div>Caricamento risultati di ${endpoint.name}...</div>
                </div>
            `;

            try {
                const startTime = performance.now();
                const response = await fetch(API_BASE_URL + endpoint.path);
                const endTime = performance.now();
                const data = await response.json();
                const duration = (endTime - startTime).toFixed(2);

                displayResults(endpoint, data, response.status, duration);
            } catch (error) {
                displayError(endpoint, error.message);
            }
        }

        function displayResults(endpoint, data, status, duration) {
            const container = document.getElementById('resultsContainer');

            let content = '';
            if (Array.isArray(data) && data.length > 0) {
                const keys = Object.keys(data[0]);
                const isSimple = keys.length <= 3 && data.length <= 20;

                if (isSimple) {
                    content = `
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>${keys.map(key => `<th>${escapeHtml(key)}</th>`).join('')}</tr>
                                </thead>
                                <tbody>
                                    ${data.map(row => `
                                        <tr>
                                            ${keys.map(key => `<td>${escapeHtml(String(row[key]))}</td>`).join('')}
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
                } else {
                    content = `
                        <div class="json-display">
                            <pre>${escapeHtml(JSON.stringify(data, null, 2))}</pre>
                        </div>
                    `;
                }
            } else if (typeof data === 'object' && data !== null && !Array.isArray(data)) {
                content = `
                    <div class="json-display">
                        <pre>${escapeHtml(JSON.stringify(data, null, 2))}</pre>
                    </div>
                `;
            } else if (Array.isArray(data) && data.length === 0) {
                content = '<p>Nessun risultato disponibile.</p>';
            } else {
                content = `<p>${escapeHtml(String(data))}</p>`;
            }

            const rowCount = Array.isArray(data) ? data.length : 1;
            const resultCount = Array.isArray(data) ? 'risultati' : 'risultato';

            container.innerHTML = `
                <div class="result-meta">
                    <div class="meta-item"><span class="meta-label">Query:</span><span class="meta-value">${endpoint.name}</span></div>
                    <div class="meta-item"><span class="meta-label">Endpoint:</span><span class="meta-value">${endpoint.path}</span></div>
                    <div class="meta-item"><span class="meta-label">Status:</span><span class="meta-value">${status}</span></div>
                    <div class="meta-item"><span class="meta-label">Tempo:</span><span class="meta-value">${duration}ms</span></div>
                    <div class="meta-item"><span class="meta-label">Righe:</span><span class="meta-value">${rowCount} ${resultCount}</span></div>
                </div>

                ${status === 200 ?
                    '<div class="success">✓ Query eseguita con successo</div>' :
                    `<div class="error">✗ Errore nella query (Status: ${status})</div>`
                }

                ${content}
            `;
        }

        function displayError(endpoint, errorMessage) {
            const container = document.getElementById('resultsContainer');
            container.innerHTML = `
                <div class="result-meta">
                    <div class="meta-item"><span class="meta-label">Query:</span><span class="meta-value">${endpoint.name}</span></div>
                    <div class="meta-item"><span class="meta-label">Endpoint:</span><span class="meta-value">${endpoint.path}</span></div>
                </div>
                <div class="error">✗ ${escapeHtml(errorMessage)}</div>
            `;
        }

        function toggleSidebar() {
            const shell = document.getElementById('appShell');
            const toggleButton = document.getElementById('toggleSidebar');
            shell.classList.toggle('sidebar-collapsed');
            toggleButton.textContent = shell.classList.contains('sidebar-collapsed') ? '▶' : '◀';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        document.addEventListener('DOMContentLoaded', () => {
            initializeSidebar();
            document.getElementById('toggleSidebar').addEventListener('click', toggleSidebar);
        });
    </script>
</body>
</html>