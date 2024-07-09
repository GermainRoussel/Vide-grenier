const { defineConfig } = require("cypress");

module.exports = defineConfig({
  projectId: '2mk241',
  fixturesFolder: "cypress/fixtures",

  e2e: {
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
  },
});
