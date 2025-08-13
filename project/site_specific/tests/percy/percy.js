const puppeteer = require('puppeteer');
const percySnapshot = require('@percy/puppeteer');
const scrollToBottom = require('scroll-to-bottomjs');
const { execSync } = require('child_process');

let site = execSync('~/.platformsh/bin/platform environment:info edge_hostname');
let siteFull = `https://${site}`;

(async () => {
    const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
    });

    const scrollOptions = {
        frequency: 100,
        timing: 200, // milliseconds
    };

    // DN Page
    const spacesPage = await browser.newPage();
    await spacesPage.goto(`${siteFull}/spaces-and-organizations/`);
    await new Promise(function (resolve) {
        setTimeout(async function () {
            await spacesPage.evaluate(scrollToBottom, scrollOptions);
            await percySnapshot(spacesPage, 'Snapshot of Spaces page');
            resolve();
        }, 3000);
    });

    await browser.close();
})();
