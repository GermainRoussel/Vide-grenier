let HOST = require("../support/host.js");
let { expect } = require('chai');
let { account } = require("../support/account.js");

describe('register', () => {
    it('Test de la création de compte', async () => {
        cy.visit(HOST + 'register');
        cy.get('#username').type(account.username);
        cy.get('#exampleInputEmail1').type(account.email);
        cy.get('#exampleInputPassword1').type(account.password);
        cy.get('#exampleInputPassword2').type(account.password);
        cy.get('[name=submit]').click();
        cy.get('#article-pattern').should('exist');
    });
});