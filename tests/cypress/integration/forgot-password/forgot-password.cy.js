describe("Testing Forgot Password Case", () => {
    
    // Positive Case
    it('user can forgot password', () => {
        cy.visit('/');
        cy.get('.text-small')
            .should('be.visible')
            .click();
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('superadmin@gmail.com');
        cy.get('[data-id="email"]')
            .should('be.enabled');
        cy.get('[data-id="button"]')
            .should('be.visible')
            .click();
    });

    it('user can input the data email', () => {
        cy.visit('/');
        cy.get('.text-small')
            .should('be.visible')
            .click();
        cy.get('[data-id="message"]')
            .should('have.text', 'We will send a link to reset your password');
        cy.get('[data-id="email"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="email"]')
            .should('have.id', 'email');
        cy.get('[data-id="email"]')
            .should('have.class', 'form-control');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('superadmin@gmail.com');
        cy.get('.btn')
            .should('be.visible')
            .click();
    });

    it('Verify Email', () => {
        cy.visit('/');
        cy.get('.text-small')
            .should('be.visible')
            .click();
        cy.get('[data-id="message"]')
            .should('have.text', 'We will send a link to reset your password');
        cy.get('[data-id="email"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="email"]')
            .should('have.id', 'email');
        cy.get('[data-id="email"]')
            .should('have.class', 'form-control');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('superadmin@gmail.com');
        cy.get('.btn')
            .should('be.visible')
            .click();
        cy.visit('http://localhost:8025/');
        cy.get(':nth-child(1) > .col-md-5 > .subject')
            .should('be.visible')
            .click();
        cy.getIframeBody()
            .find('a')
            .contains('Reset Password')
            .should('be.visible')
            .click();
    });

    // Negative Case
    it('cannot forgot password, email is empty', () => {
        cy.visit('/');
        cy.get('.text-small')
            .should('be.visible')
            .click();
        cy.get('p')
            .should('have.text', 'We will send a link to reset your password');
        cy.get('#email')
            .should('be.enabled');
        cy.get('.btn')
            .should('be.visible')
            .click();
        cy.get('#email')
            .should('have.class', 'form-control');
    });

    it('sertakan @ pada alamat gmail', () => {
        cy.visit('/');
        cy.get('.text-small')
            .should('be.visible')
            .click();
        cy.get('p')
            .should('have.text', 'We will send a link to reset your password');
        cy.get('[data-id="email"]')
            .should('have.class', 'form-control');
        cy.get('[data-id="email"]')
            .should('have.attr', 'type', 'email');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('superadmin');
        cy.get('.btn')
            .should('be.visible')
            .click();
        cy.get('[data-id="email"]')
            .should('have.class', 'form-control');
    });

    it('cannot forgot password, incorrect type @ email', () => {
        cy.visit('/');
        cy.get('.text-small')
            .should('be.visible')
            .click();
        cy.get('p')
            .should('have.text', 'We will send a link to reset your password');
        cy.get('[data-id="email"]')
            .should('have.class', 'form-control');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('@superadmin');
        cy.get('.btn')
            .should('be.visible')
            .click();
        cy.get('[data-id="email"]')
            .should('not.be.checked');
    });

    it('we cant find email user with that email address', () => {
        cy.visit('/');
        cy.get('.text-small')
            .should('be.visible')
            .click();
        cy.get('p')
            .should('have.text', 'We will send a link to reset your password');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('dummy@dummy.com');
        cy.get('.btn')
            .should('be.enabled');
        cy.get('.btn')
            .should('be.visible')
            .click();
        cy.get('[data-id="email"]')
            .should('have.class', 'is-invalid');
    });
})

Cypress.Commands.add('getIframeBody', () => {
    return cy.get('iframe#preview-html')
        .its('0.contentDocument.body')
        .should('not.be.empty')
        .then(cy.wrap);
})