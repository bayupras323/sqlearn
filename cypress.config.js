const { defineConfig } = require('cypress')
const { downloadFile } = require('cypress-downloadfile/lib/addPlugin')

module.exports = defineConfig({
    chromeWebSecurity: false,
    retries: 2,
    defaultCommandTimeout: 5000,
    watchForFileChanges: false,
    videosFolder: 'tests/cypress/videos',
    screenshotsFolder: 'tests/cypress/screenshots',
    fixturesFolder: 'tests/cypress/fixtures',
    e2e: {
        setupNodeEvents(on, config) {
            on('task', { downloadFile })
            return require('./tests/cypress/plugins/index.js')(on, config)
        },
        experimentalStudio: true,
        baseUrl: 'http://127.0.0.1:8000',
        specPattern: 'tests/cypress/integration/**/*.cy.{js,jsx,ts,tsx}',
        supportFile: 'tests/cypress/support/index.js',
    },
})
