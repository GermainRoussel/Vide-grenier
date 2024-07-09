let HOST = require("../support/host-indexAction.js");
let { expect } = require('chai');
let { account } = require("../support/account-indexAction.js");

describe('indexAction', () => {
    it('Affiche la page d ajout de produit', () => {
        cy.visit(HOST+'login');
        cy.get('#exampleInputEmail1').type(account.email);
        cy.get('#exampleInputPassword1').type(account.password);
        cy.get('[name=submit]').click();
        cy.visit(HOST + 'product');
        cy.get('.content-wrapper > .container-fluid').should('exist');
    });
});