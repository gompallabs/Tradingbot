describe('Scrape Rest Api Error Page', () => {
    it('Scrapes data from bitget error page', () => {
        cy.visit('https://bitgetlimited.github.io/apidoc/en/mix/#restapi-error-codes',{
            headers: {
                "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
                "Accept-Encoding": "gzip, deflate, br",
                "Accept-Language": "en-US,en;q=0.9",
                "User-Agent": "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36",
                "Sec-Ch-Ua": '"Chromium";v="122", "Not(A:Brand";v="24", "Google Chrome";v="122"',
                "Sec-Ch-Ua-Mobile": "?0",
                "Sec-Ch-Ua-Platform": "Linux",
                "Sec-Fetch-Dest": "document",
                "Sec-Fetch-Mode": "navigate",
                "Upgrade-Insecure-Requests": 1
            }
        });
        cy.get('h1').invoke('text').then((headerText) => {
            const allTableData = []; // An array to store data from all tables

            // Flag to indicate whether to continue processing tables
            let continueProcessing = false;

            // Find and iterate over all 'h1' elements on the page
            cy.get('h1#restapi-error-codes').then(($h1) => {
                // Find the corresponding 'table' elements by selecting the next siblings
                const $tables = $h1.nextAll('table');

                // Iterate through each table
                $tables.each((tableIndex, tableElement) => {
                    const tableRows = Cypress.$(tableElement).find('tr');
                    const tableData = [];

                    tableRows.each((rowIndex, row) => {
                        const rowData = [];
                        Cypress.$(row).find('td').each((cellIndex, cell) => {
                            rowData.push(Cypress.$(cell).text());
                        });
                        tableData.push(rowData);
                    });

                    if (tableData.length > 0) {
                        cy.log(`Parsed Table ${tableIndex + 1} Data:`, tableData);
                        allTableData.push(tableData);
                    }
                });

                cy.request({
                    method: 'POST',
                    url: 'http://localhost:8080/scraper',
                    body: {
                        repository: "bitget",
                        page: "restapi-error-codes",
                        data: JSON.stringify(allTableData)
                    },
                }).then((response) => {
                    console.log('API Response:', response);
                });
            });
        });
    });
});

