let HOST = require("../support/host-ProductsAction.js");
let { expect } = require('chai');
let { account } = require("../support/account-ProductsAction.js");

describe('ProductsAction', () => {
    it('doit afficher un article', () => {
        // Visiter la page d'ajout de produit
        cy.visit(HOST + 'login');
        cy.get('#exampleInputEmail1').type(account.email);
        cy.get('#exampleInputPassword1').type(account.password);
        cy.get('[name=submit]').click();
        cy.visit(HOST );
        cy.get('#articlelist > :nth-child(1)').should('exist');
    });
});