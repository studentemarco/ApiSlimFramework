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

        tr.detail-row {
            cursor: pointer;
        }

        tr.detail-row:hover {
            background: rgba(99, 102, 241, 0.12);
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

        .details-modal {
            position: fixed;
            inset: 0;
            background: rgba(31, 41, 55, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            z-index: 999;
        }

        .details-modal.show {
            display: flex;
        }

        .details-modal-content {
            width: min(900px, 100%);
            max-height: 85vh;
            overflow: auto;
            background: #ffffff;
            border-radius: 14px;
            box-shadow: var(--shadow);
            padding: 22px;
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 14px;
        }

        .results-title {
            font-size: 1.2rem;
            color: var(--dark);
            font-weight: 700;
        }

        .close-results {
            border: 1px solid var(--border);
            background: #ffffff;
            color: var(--dark);
            border-radius: 8px;
            width: 34px;
            height: 34px;
            cursor: pointer;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .close-results:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .detail-section + .detail-section {
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid var(--border);
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

    <div id="resultsModal" class="details-modal" aria-hidden="true">
        <div class="details-modal-content" role="dialog" aria-modal="true" aria-label="Dettagli elemento">
            <div id="resultsModalContent"></div>
        </div>
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

        const DETAIL_ENDPOINTS = {
            fornitore: {
                name: 'Dettaglio fornitore',
                description: 'Dati completi del fornitore selezionato',
                path: '/fornitore'
            },
            pezzo: {
                name: 'Dettaglio pezzo',
                description: 'Dati completi del pezzo selezionato',
                path: '/pezzo'
            }
        };

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
            renderLoading(container, `Caricamento risultati di ${endpoint.name}...`);

            try {
                const result = await fetchJson(endpoint.path);
                displayResults(endpoint, result.data, result.status, result.duration);
            } catch (error) {
                displayError(endpoint, error.message);
            }
        }

        async function displayDetails(detailRequests) {
            const modalContent = document.getElementById('resultsModalContent');
            renderLoading(modalContent, 'Caricamento dettagli...');

            const modal = document.getElementById('resultsModal');
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');

            const requests = detailRequests
                .map(item => ({ ...item, endpoint: DETAIL_ENDPOINTS[item.type] }))
                .filter(item => item.endpoint);

            if (requests.length === 0) {
                displayResultsModal(
                    { name: 'Dettaglio', description: 'Nessun identificatore disponibile' },
                    '-',
                    { error: 'Nessun identificatore disponibile per il dettaglio' },
                    400,
                    '0.00'
                );
                return;
            }

            const results = await Promise.all(requests.map(async request => {
                const path = `${request.endpoint.path}/${request.value}`;
                try {
                    const result = await fetchJson(path);
                    return { ...request, path, ...result };
                } catch (error) {
                    return {
                        ...request,
                        path,
                        data: { error: error.message },
                        status: 500,
                        duration: '0.00'
                    };
                }
            }));

            if (results.length === 1) {
                const single = results[0];
                displayResultsModal(single.endpoint, single.path, single.data, single.status, single.duration);
                return;
            }

            displayMultipleDetailsModal(results);
        }

        async function fetchJson(path) {
            const startTime = performance.now();
            const response = await fetch(`${API_BASE_URL}${path}`);
            const endTime = performance.now();
            return {
                data: await response.json(),
                status: response.status,
                duration: (endTime - startTime).toFixed(2)
            };
        }

        function renderLoading(container, message) {
            container.className = '';
            container.innerHTML = `
                <div class="loading">
                    <div class="spinner"></div>
                    <div>${escapeHtml(message)}</div>
                </div>
            `;
        }

        function displayResults(endpoint, data, status, duration) {
            const container = document.getElementById('resultsContainer');
            const { rowCount, resultCount } = getResultStats(data);
            const metaItems = [
                { label: 'Query', value: endpoint.name },
                { label: 'Endpoint', value: endpoint.path },
                { label: 'Status', value: status },
                { label: 'Tempo', value: `${duration}ms` },
                { label: 'Righe', value: `${rowCount} ${resultCount}` }
            ];

            container.innerHTML = `
                ${renderMeta(metaItems)}
                ${renderStatus(status)}
                ${renderDataContent(data, { withDetailRows: true })}
            `;

            bindDetailRows(container, data);
        }

        function displayResultsModal(endpoint, path, data, status, duration) {
            const modal = document.getElementById('resultsModal');
            const container = document.getElementById('resultsModalContent');
            const { rowCount, resultCount } = getResultStats(data);
            const metaItems = [
                { label: 'Endpoint', value: path },
                { label: 'Status', value: status },
                { label: 'Tempo', value: `${duration}ms` },
                { label: 'Righe', value: `${rowCount} ${resultCount}` }
            ];

            container.innerHTML = `
                <div class="results-header">
                        <div>
                            <div class="results-title">${escapeHtml(endpoint.name || 'Dettaglio')}</div>
                            <div style="font-size: 0.875rem; color: var(--text); opacity: 0.7; margin-top: 4px;">
                                ${escapeHtml(endpoint.description || endpoint.name || 'Dettaglio elemento')}
                            </div>
                        </div>
                        <button class="close-results" onclick="closeResults()">✕</button>
                    </div>

                ${renderMeta(metaItems)}
                ${renderStatus(status)}
                ${renderDataContent(data, { withDetailRows: false })}
            `;

            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
        }

        function displayMultipleDetailsModal(results) {
            const modal = document.getElementById('resultsModal');
            const container = document.getElementById('resultsModalContent');
            const totalDuration = results.reduce((total, result) => total + Number(result.duration || 0), 0).toFixed(2);
            const globalStatus = results.every(result => result.status === 200) ? 200 : 207;

            container.innerHTML = `
                <div class="results-header">
                    <div>
                        <div class="results-title">Dettaglio fornitore + pezzo</div>
                        <div style="font-size: 0.875rem; color: var(--text); opacity: 0.7; margin-top: 4px;">
                            La riga contiene sia identificativo fornitore che identificativo pezzo
                        </div>
                    </div>
                    <button class="close-results" onclick="closeResults()">✕</button>
                </div>

                ${renderMeta([
                    { label: 'Richieste', value: `${results.length}` },
                    { label: 'Status', value: globalStatus },
                    { label: 'Tempo totale', value: `${totalDuration}ms` }
                ])}

                ${results.map(result => renderDetailSection(result)).join('')}
            `;

            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
        }

        function renderDetailSection(result) {
            const stats = getResultStats(result.data);
            return `
                <section class="detail-section">
                    <div class="results-title" style="font-size: 1rem; margin-bottom: 10px;">
                        ${escapeHtml(result.endpoint.name)}
                    </div>
                    ${renderMeta([
                        { label: 'Endpoint', value: result.path },
                        { label: 'Status', value: result.status },
                        { label: 'Tempo', value: `${result.duration}ms` },
                        { label: 'Righe', value: `${stats.rowCount} ${stats.resultCount}` }
                    ])}
                    ${renderStatus(result.status)}
                    ${renderDataContent(result.data, { withDetailRows: false })}
                </section>
            `;
        }

        function getResultStats(data) {
            return {
                rowCount: Array.isArray(data) ? data.length : 1,
                resultCount: Array.isArray(data) ? 'risultati' : 'risultato'
            };
        }

        function renderMeta(items) {
            return `
                <div class="result-meta">
                    ${items.map(item => `
                        <div class="meta-item">
                            <span class="meta-label">${escapeHtml(String(item.label))}:</span>
                            <span class="meta-value">${escapeHtml(String(item.value))}</span>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function renderStatus(status) {
            return status === 200
                ? '<div class="success">✓ Query eseguita con successo</div>'
                : `<div class="error">✗ Errore nella query (Status: ${status})</div>`;
        }

        function renderDataContent(data, options = {}) {
            const withDetailRows = options.withDetailRows === true;

            if (Array.isArray(data) && data.length === 0) {
                return '<p>Nessun risultato disponibile.</p>';
            }

            if (Array.isArray(data) && data.length > 0) {
                const keys = Object.keys(data[0]);
                return `
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>${keys.map(key => `<th>${escapeHtml(key)}</th>`).join('')}</tr>
                            </thead>
                            <tbody>
                                ${data.map((row, index) => {
                                    const rowClass = withDetailRows && hasDetailReference(row) ? 'detail-row' : '';
                                    const rowIndex = withDetailRows ? ` data-row-index="${index}"` : '';
                                    return `
                                        <tr class="${rowClass}"${rowIndex}>
                                            ${keys.map(key => `<td>${escapeHtml(String(row[key]))}</td>`).join('')}
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }

            if (typeof data === 'object' && data !== null) {
                return `
                    <div class="json-display">
                        <pre>${escapeHtml(JSON.stringify(data, null, 2))}</pre>
                    </div>
                `;
            }

            return `<p>${escapeHtml(String(data))}</p>`;
        }

        function getRowDetailRequests(row) {
            if (!row || typeof row !== 'object') {
                return [];
            }

            const keys = Object.keys(row);
            const fidKey = keys.find(key => key.toLowerCase() === 'fid');
            const pidKey = keys.find(key => key.toLowerCase() === 'pid');
            const requests = [];

            if (pidKey && row[pidKey] !== null && row[pidKey] !== undefined && row[pidKey] !== '') {
                requests.push({ type: 'pezzo', value: row[pidKey] });
            }

            if (fidKey && row[fidKey] !== null && row[fidKey] !== undefined && row[fidKey] !== '') {
                requests.push({ type: 'fornitore', value: row[fidKey] });
            }

            return requests;
        }

        function hasDetailReference(row) {
            return getRowDetailRequests(row).length > 0;
        }

        function bindDetailRows(container, data) {
            if (!Array.isArray(data)) {
                return;
            }

            container.querySelectorAll('tr[data-row-index]').forEach(rowElement => {
                const rowIndex = Number(rowElement.dataset.rowIndex);
                const rowData = data[rowIndex];
                const detailRequests = getRowDetailRequests(rowData);

                if (detailRequests.length === 0) {
                    return;
                }

                rowElement.addEventListener('click', () => {
                    displayDetails(detailRequests);
                });
            });
        }

        function closeResults() {
            const modal = document.getElementById('resultsModal');
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
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

            const modal = document.getElementById('resultsModal');
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeResults();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeResults();
                }
            });
        });
    </script>
</body>
</html>