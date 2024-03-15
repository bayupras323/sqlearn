describe('Testing Login Page', () => {

    // Positive Case
    it('superadmin can login successfully', () => {
        cy.visit('/');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('superadmin@gmail.com')
            .blur();
        cy.get('[data-id="password"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('password')
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="greetings"]')
            .should('have.text', 'Hi, SuperAdmin');
    });
    
    it('user can login successfully', () => {
        cy.visit('/');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('user@gmail.com')
            .blur();
        cy.get('[data-id="password"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('password')
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="greetings"]')
            .should('have.text', 'Hi, user');
    });

    // Negative Case
    it('cannot login when email is empty', () => {
        cy.visit('/');
        cy.get('[data-id="password"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('password')
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
        cy.get('.invalid-feedback')
            .should('have.text', 'The email field is required.');
    });

    it('cannot login when password is empty', () => {
        cy.visit('/');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('user@gmail.com')
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
        cy.get('.invalid-feedback')
            .should('have.text', 'The password field is required.');
    });

    it('cannot login when email is not registered', () => {
        cy.visit('//');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('test@gmail.com')
            .blur();
        cy.get('[data-id="password"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('password')
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
        cy.get('.invalid-feedback')
            .should('have.text', 'These credentials do not match our records.');
    });

    it('cannot login when password is not registered', () => {
        cy.visit('/');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('superadmin@gmail.com')
            .blur();
        cy.get('[data-id="password"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('bukanpassword')
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
        cy.get('.invalid-feedback')
            .should('have.text', 'These credentials do not match our records.');
    });

    it('cannot login when email is invalid', () => {
        cy.visit('/');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('test@email.com')
            .blur();
        cy.get('[data-id="password"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('password')
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
        cy.get('.invalid-feedback')
            .should('have.text', 'These credentials do not match our records.');
    });

    it('cannot login when password is invalid', () => {
        cy.visit('/');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('superadmin@gmail.com')
            .blur();
        cy.get('[data-id="password"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('1234567=')
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
        cy.get('.invalid-feedback')
            .should('have.text', 'These credentials do not match our records.');
    });

    it('cannot login when password is too short', () => {
        cy.visit('/');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')            
            .clear()
            .type('superadmin@gmail.com')
            .blur();
        cy.get('[data-id="password"]')
            .focus()
            .should('be.visible')            
            .clear()
            .type('pass')
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
        cy.get('.invalid-feedback')
            .should('have.text', 'These credentials do not match our records.');
    });
});
