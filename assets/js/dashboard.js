async function loadDashboard() {
    // placeholders
    setText('mejaTersedia', '—');
    setText('totalTransaksi', '—');
    setText('totalPemesanan', '—');
    setText('totalPendapatan', '—');

    // fetch some endpoints in parallel but tolerate failures
    const pMeja = fetchArray('meja');
    const pPelanggan = fetchArray('pelanggan');
    const orderCandidates = ['pesanan']; // Mengambil data pesanan yang selesai
    const orderPromises = orderCandidates.map(c => fetchRaw(c));

    const [mejaRes, pelangganRes, ...orderRawResults] = await Promise.all([pMeja, pPelanggan, ...orderPromises]);

    // process meja
    const mejaArr = mejaRes.ok ? (mejaRes.body ?? []) : [];
    // process pelanggan
    const pelangganArr = pelangganRes.ok ? pelangganRes.body ?? [] : [];

    // find first successful order candidate that returns array-like useful data
    let pesananArr = [];
    for (let i = 0; i < orderRawResults.length; i++) {
        const r = orderRawResults[i];
        if (!r) continue;
        if (!r.ok) continue;

        let body = r.body;
        // if body is string (non-json) ignore
        if (typeof body === 'string') {
            try { body = JSON.parse(body); } catch (e) { body = null; }
        }
        if (!body) continue;

        if (Array.isArray(body)) {
            pesananArr = body.filter(p => p.status_pesanan === 'selesai'); // Hanya pesanan yang selesai
            break;
        }
        if (Array.isArray(body.data)) {
            pesananArr = body.data.filter(p => p.status_pesanan === 'selesai');
            break;
        }
    }

    // Debugging: log the data received
    console.log('Pesanan Data:', pesananArr);

    // Check if pesananArr is an array, otherwise set it to an empty array
    if (!Array.isArray(pesananArr)) {
        pesananArr = [];
    }

    // compute cards
    const mejaTersedia = Array.isArray(mejaArr) ? mejaArr.filter(m => (m.status_meja ?? '').toString().toLowerCase() === 'tersedia').length : 0;
    const totalTransaksi = pesananArr.length;
    const totalPemesanan = pesananArr.length;
    const totalPendapatan = pesananArr.reduce((s, it) => s + Number(it.total_harga ?? 0), 0);

    setText('mejaTersedia', mejaTersedia);
    setText('totalTransaksi', totalTransaksi);
    setText('totalPemesanan', totalPemesanan);
    setText('totalPendapatan', formatCurrency(totalPendapatan));

    // try map pelanggan names
    let rows = pesananArr.slice();
    if (rows.length && Array.isArray(pelangganArr) && pelangganArr.length) {
        const map = {};
        pelangganArr.forEach(p => {
            const k = p.id_pelanggan ?? p.id ?? p.customer_id;
            if (k != null) map[k] = p.nama_pelanggan ?? p.nama ?? p.customer_name;
        });
        rows = rows.map(r => {
            const k = r.id_pelanggan ?? r.customer_id ?? r.id_customer;
            if (!r.nama_pelanggan && k && map[k]) r.nama_pelanggan = map[k];
            return r;
        });
    }

    rows.sort((a, b) => new Date(b.created_at ?? 0) - new Date(a.created_at ?? 0));

    renderRows(rows);

    if (totalPemesanan === 0) {
        console.warn('Tidak menemukan data pesanan. Cek candidate statuses:');
        orderCandidates.forEach((c, idx) => {
            const r = orderRawResults[idx];
            if (!r) { console.log(c, '-> no response'); return; }
            console.log(c, '->', r.ok ? `OK ${Array.isArray(r.body) ? 'array' : ''}` : `HTTP ${r.status}`, r.body && typeof r.body === 'object' ? r.body : r.rawText);
        });
    }
}
