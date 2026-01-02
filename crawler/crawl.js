const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

// =========================
// 1. Baca routes.json
// =========================
let routesRaw = fs.readFileSync('../routes.json', 'utf8');
routesRaw = routesRaw.replace(/^\uFEFF/, ''); // hapus BOM
const routesData = JSON.parse(routesRaw);

// =========================
// 2. Filter route GET HTML
// =========================
const routes = routesData
  .filter(r =>
      r.method.includes('GET') &&
      !r.uri.startsWith('_') &&        // skip Ignition/internal
      !r.uri.startsWith('api') &&      // skip API
      !r.uri.includes('export') &&     // skip export/download
      !['login','register','forgot-password','reset-password','confirm-password','verify-email'].includes(r.uri.split('/')[0])
  )
  .map(r => '/' + r.uri.replace(/^\/|\/$/g, ''));

// =========================
// 3. Crawl dengan Puppeteer
// =========================
(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();

    for (const route of routes) {
        try {
            const url = `http://127.0.0.1:8000${route}`;
            console.log(`Crawling ${url}...`);

            await page.goto(url, { waitUntil: 'networkidle0' });
            let html = await page.content();

            // =========================
            // 4. Ubah semua href localhost ke relative HTML
            // =========================
            html = html.replace(/http:\/\/127\.0\.0\.1:8000(\/[a-zA-Z0-9\-\/]*)/g, (_, p1) => {
                let fileName = p1 === '/' ? 'index.html' : p1.replace(/\//g, '-') + '.html';
                return './' + fileName;
            });

            // =========================
            // 5. Simpan HTML
            // =========================
            let fileName = route === '/' ? 'index.html' : route.replace(/\//g, '-') + '.html';
            const filePath = path.join(__dirname, 'output', fileName);

            fs.mkdirSync(path.dirname(filePath), { recursive: true });
            fs.writeFileSync(filePath, html);

            console.log(`Saved ${fileName}`);
        } catch (err) {
            console.log(`Skipped ${route} (error: ${err.message})`);
        }
    }

    await browser.close();
    console.log('All routes exported!');
})();
