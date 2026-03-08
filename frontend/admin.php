<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/frontend/common.css">
</head>
<body>
    <h2>Dashboard Amministratore</h2>

    <div class="box">
        <div class="row">
            <input id="username" placeholder="username" value="admin" />
            <input id="password" type="password" placeholder="password" value="admin123" />
            <button onclick="doLogin()">Login</button>
            <span id="loginStatus" class="muted">non autenticato</span>
        </div>
    </div>

    <div class="box">
        <div class="row">
            <label>Risorsa</label>
            <select id="resource" onchange="resetPageAndLoad()">
                <option value="fornitori">Fornitori</option>
                <option value="pezzi">Pezzi</option>
                <option value="catalogo">Catalogo</option>
            </select>
            <button onclick="loadData()">Ricarica</button>
            <button onclick="prevPage()">Prev</button>
            <button onclick="nextPage()">Next</button>
            <span id="pager" class="muted"></span>
        </div>
    </div>

    <div class="box">
        <strong>Crea record</strong>
        <div id="createForm"></div>
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
        let page = 1;
        const perPage = 10;
        let meta = null;

        async function doLogin() {
            auth.username = document.getElementById('username').value.trim();
            auth.password = document.getElementById('password').value.trim();
            try {
                const me = await api('/auth/me');
                if (me.ruolo !== 'admin') throw new Error('Utente non admin');
                FrontendCommon.setText('loginStatus', `ok: ${me.username} (${me.ruolo})`);
                page = 1;
                renderCreateForm();
                loadData();
            } catch (e) {
                FrontendCommon.setText('loginStatus', e.message);
            }
        }

        function getResource() {
            return document.getElementById('resource').value;
        }

        function resetPageAndLoad() {
            page = 1;
            renderCreateForm();
            loadData();
        }

        function renderCreateForm() {
            const r = getResource();
            const root = document.getElementById('createForm');
            if (r === 'fornitori') {
                root.innerHTML = `
                    <input id="c_fnome" placeholder="fnome" />
                    <input id="c_indirizzo" placeholder="indirizzo" />
                    <button onclick="createRecord()">Crea fornitore</button>
                `;
            } else if (r === 'pezzi') {
                root.innerHTML = `
                    <input id="c_pnome" placeholder="pnome" />
                    <input id="c_colore" placeholder="colore" />
                    <button onclick="createRecord()">Crea pezzo</button>
                `;
            } else {
                root.innerHTML = `
                    <input id="c_fid" type="number" placeholder="fid" />
                    <input id="c_pid" type="number" placeholder="pid" />
                    <input id="c_costo" type="number" placeholder="costo" />
                    <button onclick="createRecord()">Crea catalogo</button>
                `;
            }
        }

        async function createRecord() {
            const r = getResource();
            try {
                if (r === 'fornitori') {
                    await api('/fornitori', {
                        method: 'POST',
                        body: JSON.stringify({
                            fnome: document.getElementById('c_fnome').value,
                            indirizzo: document.getElementById('c_indirizzo').value
                        })
                    });
                } else if (r === 'pezzi') {
                    await api('/pezzi', {
                        method: 'POST',
                        body: JSON.stringify({
                            pnome: document.getElementById('c_pnome').value,
                            colore: document.getElementById('c_colore').value
                        })
                    });
                } else {
                    await api('/catalogo', {
                        method: 'POST',
                        body: JSON.stringify({
                            fid: Number(document.getElementById('c_fid').value),
                            pid: Number(document.getElementById('c_pid').value),
                            costo: Number(document.getElementById('c_costo').value)
                        })
                    });
                }
                loadData();
            } catch (e) {
                alert(e.message);
            }
        }

        async function loadData() {
            const r = getResource();
            try {
                const data = await api(`/${r}?page=${page}&per_page=${perPage}`);
                meta = data.meta || null;
                FrontendCommon.renderSimpleTable('dataTable', data.data || [], actionButtons);
                FrontendCommon.updatePager('pager', meta);
            } catch (e) {
                alert(e.message);
            }
        }

        function actionButtons(r) {
            const res = getResource();
            if (res === 'fornitori') {
                return `
                    <button onclick='showDetails("fornitore", ${r.fid})'>Dettagli</button>
                    <button onclick='editFornitore(${r.fid}, ${JSON.stringify(r.fnome)}, ${JSON.stringify(r.indirizzo)})'>Modifica</button>
                    <button onclick='deleteFornitore(${r.fid})'>Elimina</button>
                `;
            }
            if (res === 'pezzi') {
                return `
                    <button onclick='showDetails("pezzo", ${r.pid})'>Dettagli</button>
                    <button onclick='editPezzo(${r.pid}, ${JSON.stringify(r.pnome)}, ${JSON.stringify(r.colore)})'>Modifica</button>
                    <button onclick='deletePezzo(${r.pid})'>Elimina</button>
                `;
            }
            return `
                <button onclick='showCatalogoDetails(${r.fid}, ${r.pid})'>Dettagli</button>
                <button onclick='editCatalogo(${r.fid}, ${r.pid}, ${r.costo})'>Modifica</button>
                <button onclick='deleteCatalogo(${r.fid}, ${r.pid})'>Elimina</button>
            `;
        }

        async function showDetails(type, id) {
            try {
                const d = await api(`/${type}/${id}`);
                FrontendCommon.showDialogJson('detailDialog', 'detailContent', d);
            } catch (e) {
                alert(e.message);
            }
        }

        async function showCatalogoDetails(fid, pid) {
            try {
                const c = await api(`/catalogo/${fid}/${pid}`);
                const p = await api(`/pezzo/${pid}`);
                const f = await api(`/fornitore/${fid}`);
                FrontendCommon.showDialogJson('detailDialog', 'detailContent', { catalogo: c, pezzo: p, fornitore: f });
            } catch (e) {
                alert(e.message);
            }
        }

        async function editFornitore(fid, fnome, indirizzo) {
            const n = prompt('Nuovo nome fornitore', fnome);
            if (n === null) return;
            const i = prompt('Nuovo indirizzo', indirizzo);
            if (i === null) return;
            try {
                await api(`/fornitori/${fid}`, { method: 'PUT', body: JSON.stringify({ fnome: n, indirizzo: i }) });
                loadData();
            } catch (e) { alert(e.message); }
        }

        async function deleteFornitore(fid) {
            if (!confirm('Eliminare fornitore?')) return;
            try {
                await api(`/fornitori/${fid}`, { method: 'DELETE' });
                loadData();
            } catch (e) { alert(e.message); }
        }

        async function editPezzo(pid, pnome, colore) {
            const n = prompt('Nuovo nome pezzo', pnome);
            if (n === null) return;
            const c = prompt('Nuovo colore', colore);
            if (c === null) return;
            try {
                await api(`/pezzi/${pid}`, { method: 'PUT', body: JSON.stringify({ pnome: n, colore: c }) });
                loadData();
            } catch (e) { alert(e.message); }
        }

        async function deletePezzo(pid) {
            if (!confirm('Eliminare pezzo?')) return;
            try {
                await api(`/pezzi/${pid}`, { method: 'DELETE' });
                loadData();
            } catch (e) { alert(e.message); }
        }

        async function editCatalogo(fid, pid, costo) {
            const c = prompt('Nuovo costo', costo);
            if (c === null) return;
            try {
                await api(`/catalogo/${fid}/${pid}`, { method: 'PUT', body: JSON.stringify({ costo: Number(c) }) });
                loadData();
            } catch (e) { alert(e.message); }
        }

        async function deleteCatalogo(fid, pid) {
            if (!confirm('Eliminare riga catalogo?')) return;
            try {
                await api(`/catalogo/${fid}/${pid}`, { method: 'DELETE' });
                loadData();
            } catch (e) { alert(e.message); }
        }

        function prevPage() {
            if (page > 1) { page -= 1; loadData(); }
        }

        function nextPage() {
            if (!meta) return;
            if (page < meta.total_pages) { page += 1; loadData(); }
        }

        renderCreateForm();
    </script>
</body>
</html>
