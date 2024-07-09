let HOST = require("../support/host-sendEmailAction.js");
let { expect } = require('chai');
let { account } = require("../support/account-sendEmailAction.js");

describe('add-product', () => {
    it('doit afficher un article', () => {
        cy.intercept('POST', '/send-email').as('sendEmail');
        // Visiter la page d'ajout de produit
        cy.visit(HOST + 'login');
        cy.get('#exampleInputEmail1').type(account.email);
        cy.get('#exampleInputPassword1').type(account.password);
        cy.get('[name=submit]').click();
        cy.visit(HOST + 'product/1');
        cy.get('#contactSellerBtn').click();
        cy.get('#emailSubject').type('Test email')
        cy.get('#emailContent').type('Test email content, do not respond.')
        cy.get("#contactSellerForm > .btn").click();

        cy.wait('@sendEmail').its('response.body').should('deep.equal', { message: 'Email sent successfully' });
    });
});