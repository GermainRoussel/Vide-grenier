let HOST = require("../support/host-showAction.js");
let { expect } = require('chai');
let { account } = require("../support/account-showAction.js");

describe('showAction', () => {
    it('doit afficher l artcile id1', () => {
        cy.visit(HOST + 'login');
        cy.get('#exampleInputEmail1').type(account.email);
        cy.get('#exampleInputPassword1').type(account.password);
        cy.get('[name=submit]').click();
        cy.visit(HOST + 'product/1');
        cy.get('.info-content > p').should('have.text','Carte du monde à gratter. Neuve dans son emballage d’origine');
    });
});