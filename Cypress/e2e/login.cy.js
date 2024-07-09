let HOST = require("../support/host-login.js");
let {expect} = require('chai');

let {account} = require("../support/account-login.js");

describe('login', () => {
   
    
    it('test connection au site', () => {
        cy.visit(HOST+'login');
        cy.get('#exampleInputEmail1').type(account.email);
        cy.get('#exampleInputPassword1').type(account.password);
        cy.get('[name=submit]').click();
        cy.get('.channel > .container-fluid').should('exist');
      
      })
})