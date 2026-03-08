<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo Fornitore</title>
    <link rel="stylesheet" href="/frontend/common.css">
</head>
<body>
    <h2>Catalogo Fornitore</h2>

    <div class="box">
        <div class="row">
            <input id="username" placeholder="username" value="acme" />
            <input id="password" type="password" placeholder="password" value="acme123" />
            <button onclick="doLogin()">Login</button>
            <span id="loginStatus" class="muted">non autenticato</span>
        </div>
    </div>

    <div class="box">
        <strong>Aggiungi pezzo al catalogo</strong>
        <div class="row">
            <input id="newPid" type="number" placeholder="pid" />
            <input id="newCosto" type="number" placeholder="costo" />
            <button onclick="addCatalogo()">Aggiungi</button>
        </div>
    </div>

    <div class="box">
        <div class="row">
            <button onclick="loadCatalogo()">Ricarica</button>
            <button onclick="prevPage()">Prev</button>
            <button onclick="nextPage()">Next</button>
            <span id="pager" class="muted"></span>
        </div>
    </div>

    <div class="box">
        <table id="dataTable"></table>
    </div>

    <dialog id="detailDialog">
        <h3>Dettagli</h3>
        <pre id="detailContent"></pre>
        <button onclick="document.getElementById('detailDialog').close()">Chiudi</button>
    </dialog>

    <script src="/frontend/config.js"></script>
    <script src="/frontend/common.js"></script>
    <script>
        let auth = { username: '', password: '' };
        const api = FrontendCommon.createApi(auth);
        let me = null;
        let page = 1;
        const perPage = 10;
        let meta = null;

        async function doLogin() {
            auth.username = document.getElementById('username').value.trim();
            auth.password = document.getElementById('password').value.trim();
            try {
                me = await api('/auth/me');
                if (me.ruolo !== 'fornitore') throw new Error('Utente non fornitore');
                FrontendCommon.setText('loginStatus', `ok: ${me.username} (fid ${me.fid})`);
                page = 1;
                loadCatalogo();
            } catch (e) {
                FrontendCommon.setText('loginStatus', e.message);
            }
        }

        async function loadCatalogo() {
            if (!me) return;
            try {
                const d = await api(`/catalogo?page=${page}&per_page=${perPage}`);
                meta = d.meta || null;
                FrontendCommon.renderSimpleTable('dataTable', d.data || [], function (r) {
                    return `
                        <button onclick="showDetails(${r.fid}, ${r.pid})">Dettagli</button>
                        <button onclick="editCosto(${r.fid}, ${r.pid}, ${r.costo})">Modifica</button>
                        <button onclick="deleteRow(${r.fid}, ${r.pid})">Elimina</button>
                    `;
                });
                FrontendCommon.updatePager('pager', meta);
            } catch (e) {
                alert(e.message);
            }
        }

        async function addCatalogo() {
            if (!me) return;
            const pid = Number(document.getElementById('newPid').value);
            const costo = Number(document.getElementById('newCosto').value);
            try {
                await api('/catalogo', {
                    method: 'POST',
                    body: JSON.stringify({ fid: me.fid, pid: pid, costo: costo })
                });
                loadCatalogo();
            } catch (e) {
                alert(e.message);
            }
        }

        async function editCosto(fid, pid, costo) {
            const c = prompt('Nuovo costo', costo);
            if (c === null) return;
            try {
                await api(`/catalogo/${fid}/${pid}`, {
                    method: 'PUT',
                    body: JSON.stringify({ costo: Number(c) })
                });
                loadCatalogo();
            } catch (e) {
                alert(e.message);
            }
        }

        async function deleteRow(fid, pid) {
            if (!confirm('Eliminare riga catalogo?')) return;
            try {
                await api(`/catalogo/${fid}/${pid}`, { method: 'DELETE' });
                loadCatalogo();
            } catch (e) {
                alert(e.message);
            }
        }

        async function showDetails(fid, pid) {
            try {
                const c = await api(`/catalogo/${fid}/${pid}`);
                const p = await api(`/pezzo/${pid}`);
                FrontendCommon.showDialogJson('detailDialog', 'detailContent', { catalogo: c, pezzo: p });
            } catch (e) {
                alert(e.message);
            }
        }

        function prevPage() {
            if (page > 1) { page -= 1; loadCatalogo(); }
        }

        function nextPage() {
            if (!meta) return;
            if (page < meta.total_pages) { page += 1; loadCatalogo(); }
        }
    </script>
</body>
</html>
