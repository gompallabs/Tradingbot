describe('Scrape Rest Api Error Page', () => {
    it('Scrapes data from bybit error page', () => {
        cy.visit('https://bybit-exchange.github.io/docs/v5/error',{
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

        cy.get('h2#uta--classic-account').then(($h2) => {

            const $tables = $h2.next('table')
            const allTableData = []

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
            })
            cy.request({
                method: 'POST',
                url: 'http://localhost:8080/scraper',
                body: {
                    repository: "bybit",
                    page: "restapi-error-codes",
                    data: JSON.stringify(allTableData)
                },
            }).then((response) => {
               console.log('API Response:', response);
            });
        })
    });
});

