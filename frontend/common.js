(function (w) {
    const config = w.APP_CONFIG || {};
    const apiBase = (config.API_BASE_URL || w.location.origin).replace(/\/$/, '');

    function createApi(authRef) {
        return async function api(path, options = {}) {
            const response = await fetch(apiBase + path, {
                ...options,
                headers: {
                    'Content-Type': 'application/json',
                    'X-Username': authRef.username || '',
                    'X-Password': authRef.password || '',
                    ...(options.headers || {})
                }
            });

            const text = await response.text();
            const data = text ? JSON.parse(text) : {};
            if (!response.ok) {
                throw new Error(data.error || 'Errore API');
            }
            return data;
        };
    }

    function setText(id, text) {
        const el = document.getElementById(id);
        if (el) {
            el.textContent = text;
        }
    }

    function renderSimpleTable(tableId, rows, actionBuilder) {
        const table = document.getElementById(tableId);
        if (!table) {
            return;
        }

        if (!rows || rows.length === 0) {
            table.innerHTML = '<tr><td>Nessun dato</td></tr>';
            return;
        }

        const keys = Object.keys(rows[0]);
        const head = '<tr>' + keys.map((k) => '<th>' + k + '</th>').join('') + '<th>Azioni</th></tr>';
        const body = rows.map((row) => {
            const cols = keys.map((k) => '<td>' + row[k] + '</td>').join('');
            const actions = actionBuilder ? actionBuilder(row) : '';
            return '<tr>' + cols + '<td>' + actions + '</td></tr>';
        }).join('');

        table.innerHTML = head + body;
    }

    function showDialogJson(dialogId, contentId, payload) {
        const content = document.getElementById(contentId);
        const dialog = document.getElementById(dialogId);
        if (!content || !dialog) {
            return;
        }

        content.textContent = JSON.stringify(payload, null, 2);
        dialog.showModal();
    }

    function updatePager(pagerId, meta) {
        if (!meta) {
            setText(pagerId, '');
            return;
        }

        setText(
            pagerId,
            'pagina ' + meta.page + '/' + meta.total_pages + ' - totale ' + meta.total
        );
    }

    w.FrontendCommon = {
        apiBase,
        createApi,
        setText,
        renderSimpleTable,
        showDialogJson,
        updatePager
    };
})(window);
