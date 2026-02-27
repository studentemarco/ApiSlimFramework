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
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1f2937;
            --light: #f9fafb;
            --border: #e5e7eb;
            --text: #374151;
            --shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--text);
            padding-bottom: 40px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            background: white;
            padding: 40px 0;
            margin-bottom: 40px;
            box-shadow: var(--shadow-sm);
        }

        .header-content {
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            color: var(--dark);
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 1rem;
            color: var(--text);
            opacity: 0.7;
            margin-bottom: 20px;
        }

        .api-status {
            display: inline-block;
            padding: 8px 16px;
            background: var(--success);
            color: white;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Main Content */
        main {
            margin-bottom: 40px;
        }

        /* Endpoints Grid */
        .endpoints-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .endpoint-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .endpoint-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow);
            border-color: var(--primary);
        }

        .endpoint-number {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 12px;
            font-size: 1.25rem;
        }

        .endpoint-method {
            display: inline-block;
            background: var(--success);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
            margin-left: 8px;
        }

        .endpoint-path {
            font-family: 'Courier New', monospace;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.875rem;
            margin-top: 8px;
        }

        .endpoint-description {
            color: var(--text);
            margin-top: 12px;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .endpoint-button {
            width: 100%;
            margin-top: 16px;
            padding: 12px 16px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .endpoint-button:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
        }

        .endpoint-button.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Modal Overlay */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.show {
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
        }

        /* Modal Content */
        .modal-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 900px;
            max-height: 85vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.9);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .modal-overlay.show .modal-content {
            transform: scale(1);
        }

        /* Results Section (now inside modal) */
        .results-section {
            padding: 32px;
            overflow-y: auto;
            flex: 1;
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--border);
        }

        .results-title {
            font-size: 1.5rem;
            color: var(--dark);
            font-weight: 600;
        }

        .close-results {
            background: var(--light);
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
        }

        .close-results:hover {
            background: var(--danger);
            color: white;
            transform: rotate(90deg);
        }

        /* Loading State */
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .spinner {
            border: 4px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 0.8s linear infinite;
            margin-right: 16px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Results Table */
        .table-responsive {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
        }

        tr:hover {
            background: var(--light);
        }

        /* JSON Display */
        .json-display {
            background: var(--light);
            border-left: 4px solid var(--primary);
            padding: 16px;
            border-radius: 8px;
            overflow-auto;
            max-height: 400px;
        }

        .json-display pre {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .empty-state-text {
            color: var(--text);
            opener: 0.7;
            font-size: 1.1rem;
        }

        /* Error State */
        .error {
            background: #fee2e2;
            border-left: 4px solid var(--danger);
            padding: 16px;
            border-radius: 8px;
            color: var(--danger);
            margin-top: 16px;
        }

        /* Success State */
        .success {
            background: #dcfce7;
            border-left: 4px solid var(--success);
            padding: 16px;
            border-radius: 8px;
            color: var(--success);
            margin-top: 16px;
        }

        .result-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .meta-label {
            font-weight: 600;
            color: var(--dark);
        }

        .meta-value {
            color: var(--primary);
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            h1 {
                font-size: 1.75rem;
            }

            .endpoints-grid {
                grid-template-columns: 1fr;
            }

            .results-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .close-results {
                align-self: flex-end;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .results-section {
            animation: fadeIn 0.3s ease;
        }

        .endpoint-card {
            animation: fadeIn 0.3s ease;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <h1>🚀 API Dashboard</h1>
                <p class="subtitle">Slim Framework - Test Your Endpoints</p>
                <!-- <span class="api-status">✓ API Active</span> -->
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="endpoints-grid" id="endpointsGrid"></div>
        </div>
    </main>

    <!-- Modal per i risultati -->
    <div id="resultsModal" class="modal-overlay" onclick="closeModalOnOverlay(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div id="resultsContainer" class="results-section"></div>
        </div>
    </div>

    <script>
        const API_BASE_URL = 'https://shiny-space-bassoon-r474j6rqqv9q3pgpq-8000.app.github.dev';
        const endpoints = [
            {
                id: 0,
                method: 'GET',
                path: '/0',
                description: 'Lista delle tabelle'
            },
            {
                id: 1,
                method: 'GET',
                path: '/1',
                description: 'Pezzi con almeno un fornitore'
            },
            {
                id: 2,
                method: 'GET',
                path: '/2',
                description: 'Fornitori che forniscono ogni pezzo'
            },
            {
                id: 3,
                method: 'GET',
                path: '/3',
                description: 'Fornitori che forniscono tutti i pezzi rossi'
            },
            {
                id: 4,
                method: 'GET',
                path: '/4',
                description: 'Pezzi forniti solo da Acme'
            },
            {
                id: 5,
                method: 'GET',
                path: '/5',
                description: 'Fornitori che ricaricano sopra media'
            },
            {
                id: 6,
                method: 'GET',
                path: '/6',
                description: 'Fornitore con ricarico massimo per ogni pezzo'
            },
            {
                id: 7,
                method: 'GET',
                path: '/7',
                description: 'Fornitori che forniscono solo pezzi rossi'
            },
            {
                id: 8,
                method: 'GET',
                path: '/8',
                description: 'Fornitori che forniscono rosso e verde'
            },
            {
                id: 9,
                method: 'GET',
                path: '/9',
                description: 'Fornitori che forniscono rosso o verde'
            },
            {
                id: 10,
                method: 'GET',
                path: '/10',
                description: 'Pezzi forniti da almeno 2 fornitori'
            }
        ];

        // Initialize grid
        function initializeGrid() {
            const grid = document.getElementById('endpointsGrid');
            grid.innerHTML = endpoints.map(endpoint => `
                <div class="endpoint-card">
                    <div class="endpoint-number">${endpoint.id}</div>
                    <div>
                        <span class="endpoint-method">${endpoint.method}</span>
                    </div>
                    <div class="endpoint-path">${endpoint.path}</div>
                    <p class="endpoint-description">${endpoint.description}</p>
                    <button class="endpoint-button" onclick="callEndpoint(${endpoint.id}, '${endpoint.path}')">
                        Esegui Query
                    </button>
                </div>
            `).join('');
        }

        // Call API endpoint
        async function callEndpoint(id, path) {
            const button = event.target;
            button.classList.add('loading');
            button.disabled = true;
            button.textContent = 'Caricamento...';

            try {
                const startTime = performance.now();
                const response = await fetch(API_BASE_URL + path);
                const endTime = performance.now();
                const data = await response.json();

                const duration = (endTime - startTime).toFixed(2);
                displayResults(id, path, data, response.status, duration);
            } catch (error) {
                displayError(id, path, error.message);
            } finally {
                button.classList.remove('loading');
                button.disabled = false;
                button.textContent = 'Esegui Query';
            }
        }

        // Display results
        function displayResults(id, path, data, status, duration) {
            const modal = document.getElementById('resultsModal');
            const container = document.getElementById('resultsContainer');
            const endpoint = endpoints.find(e => e.id === id);

            let content = '';

            if (Array.isArray(data) && data.length > 0) {
                // Check if it's simple objects with few properties
                const keys = Object.keys(data[0]);
                const isSimple = keys.length <= 3 && data.length <= 20;

                if (isSimple) {
                    // Table view
                    content = `
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        ${keys.map(key => `<th>${key}</th>`).join('')}
                                    </tr>
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
                    // JSON view for complex data
                    content = `
                        <div class="json-display">
                            <pre>${JSON.stringify(data, null, 2)}</pre>
                        </div>
                    `;
                }
            } else if (typeof data === 'object' && data !== null && !Array.isArray(data)) {
                // Single object
                content = `
                    <div class="json-display">
                        <pre>${JSON.stringify(data, null, 2)}</pre>
                    </div>
                `;
            } else {
                content = '<p class="empty-state-text">No data returned</p>';
            }

            const rowCount = Array.isArray(data) ? data.length : 1;
            const resultCount = Array.isArray(data) ? 'risultati' : 'risultato';

            container.innerHTML = `
                <div class="results-header">
                        <div>
                            <div class="results-title">Risultati Query #${id}</div>
                            <div style="font-size: 0.875rem; color: var(--text); opacity: 0.7; margin-top: 4px;">
                                ${endpoint.description}
                            </div>
                        </div>
                        <button class="close-results" onclick="closeResults()">✕</button>
                    </div>

                    <div class="result-meta">
                        <div class="meta-item">
                            <span class="meta-label">Endpoint:</span>
                            <span class="meta-value">${path}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Status:</span>
                            <span class="meta-value">${status}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Tempo:</span>
                            <span class="meta-value">${duration}ms</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Righe:</span>
                            <span class="meta-value">${rowCount} ${resultCount}</span>
                        </div>
                    </div>

                    ${status === 200 ? `
                        <div class="success">
                            ✓ Query eseguita con successo
                        </div>
                    ` : `
                        <div class="error">
                            ✗ Errore nella query (Status: ${status})
                        </div>
                    `}

                ${content}
            `;

            // Show modal
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        }

        // Display error
        function displayError(id, path, errorMessage) {
            const modal = document.getElementById('resultsModal');
            const container = document.getElementById('resultsContainer');
            const endpoint = endpoints.find(e => e.id === id);

            container.innerHTML = `
                <div class="results-header">
                    <div>
                        <div class="results-title">Errore Query #${id}</div>
                        <div style="font-size: 0.875rem; color: var(--text); opacity: 0.7; margin-top: 4px;">
                            ${endpoint.description}
                        </div>
                    </div>
                    <button class="close-results" onclick="closeResults()">✕</button>
                </div>

                <div class="error">
                    ✗ ${escapeHtml(errorMessage)}
                </div>
            `;

            // Show modal
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        }

        // Close results
        function closeResults() {
            const modal = document.getElementById('resultsModal');
            modal.classList.remove('show');
            setTimeout(() => {
                document.getElementById('resultsContainer').innerHTML = '';
            }, 300);
        }

        // Close modal on overlay click
        function closeModalOnOverlay(event) {
            if (event.target.id === 'resultsModal') {
                closeResults();
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeResults();
            }
        });

        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', initializeGrid);
    </script>
</body>
</html>
