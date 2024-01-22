# Portfolio Project Instructions:

### To use the project, follow these steps:

- Use $ make init to create the database and run migrations.
- Add your custodian(s) by running $ bin/console app:create-asset-repo.
- Create API keys and add them to the .env files.
Please note that major US providers are not supported due to messy APIs.

### Concept and Objectives:

This portfolio management app aims to:

1. Provide a global dashboard for viewing your current allocation, assets, and performance.
2. Enable you to input portfolio transactions based on your strategy.
3. Define a portfolio as a group of accounts.

For a portfolio, consider any account with an API. If there is no API available, you can still update your dashboard by inputting or importing transaction logs.
We offer essential tools for tasks like rebalancing allocations and assessing risk. However, this project does not include a trading bot.
If you intend to create a trading bot, it should generate signals. The portfolio can incorporate these signals and execute trades accordingly.
