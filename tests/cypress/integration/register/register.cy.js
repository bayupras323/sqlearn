describe('Register', () => {

    before(() => {
        // Reset database
        cy.exec('php artisan migrate:refresh --seed', { timeout: 600000 });
    });

    // Positive Case
    it('Registers a new user successfully.', () => {
        cy.visit('/register');
        cy.get('[data-id="fullname_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('test')
            .blur();
        cy.get('[data-id="email_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('test@test')
            .blur();
        cy.get('[data-id="password_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="password_confirmation"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="register"]')
            .should('be.visible')
            .click();
        cy.visit('http://localhost:8025/');
        cy.get(':nth-child(1) > .col-sm-4')
            .should('be.visible')
            .click();
        cy.getIframeBody()
            .find('a')
            .contains('Verify Email Address')
            .click()
        cy.visit('/dashboard');
        cy.contains('test');
    });

    // Negative Case
    it('Shows an error if the email is already in use.', () => {
        cy.visit('/register');
        cy.get('[data-id="fullname_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('test')
            .blur();
        cy.get('[data-id="email_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('test@test')
            .blur();
        cy.get('[data-id="password_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="password_confirmation"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="register"]')
            .should('be.visible')
            .click();
        cy.contains('The email has already been taken.')
    });

    it('Shows an error if the password is less than 8 characters.', () => {
        cy.visit('/register');
        cy.get('[data-id="fullname_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('admin')
            .blur();
        cy.get('[data-id="email_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('admin@test')
            .blur();
        cy.get('[data-id="password_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('1234')
            .blur();
        cy.get('[data-id="password_confirmation"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('1234')
            .blur();
        cy.get('[data-id="register"]')
            .should('be.visible')
            .click();
        cy.contains('The password must be at least 8 characters.')
    });

    it('Shows an error if the passwords dont match.', () => {
        cy.visit('/register');
        cy.get('[data-id="fullname_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('admin')
            .blur();
        cy.get('[data-id="email_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('admin@test')
            .blur();
        cy.get('[data-id="password_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="password_confirmation"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('password')
            .blur();
        cy.get('[data-id="register"]')
            .should('be.visible')
            .click();
        cy.contains('The password confirmation does not match.')
    });

    it('Shows error if the form is empty.', () => {
        cy.visit('/register');
        cy.get('[data-id="register"]')
            .should('be.visible')
            .click();
        cy.contains('field is required')
    });

    it('Shows error if full name form is empty.', () => {
        cy.visit('/register');
        cy.get('[data-id="email_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('admin@test')
            .blur();
        cy.get('[data-id="password_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="password_confirmation"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="register"]')
            .should('be.visible')
            .click();
        cy.contains('The name field is required.')
    });

    it('Displays email must not empty', () => {
        cy.visit('/register');
        cy.get('[data-id="fullname_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('user name')
            .blur();
        cy.get('[data-id="password_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="password_confirmation"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="register"]')
            .should('be.visible')
            .click();
    });

    it('Displays password must not empty', () => {
        cy.visit('/register');
        cy.get('[data-id="fullname_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('user name')
            .blur();
        cy.get('[data-id="email_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('email@gmail.com')
            .blur();
        cy.get('[data-id="password_confirmation"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="register"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="invalid_password_register"]')
            .should('have.text', '\n                                                        The password field is required.\n                                                    ');
    });

    it('Displays password confirmation must not empty', () => {
        cy.visit('/register');
        cy.get('[data-id="fullname_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('user name')
            .blur();
        cy.get('[data-id="email_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('email@gmail.com')
            .blur();
        cy.get('[data-id="password_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="register"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="invalid_password_register"]')
            .should('have.text', '\n                                                        The password confirmation does not match.\n                                                    ');
    });

    it('Displays email invalid', () => {
        cy.visit('/register');
        cy.get('[data-id="fullname_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('user name')
            .blur();
        cy.get('[data-id="email_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('email')
            .blur();
        cy.get('[data-id="password_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="password_confirmation"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="register"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="email_register"]')
            .invoke('prop', 'validationMessage')
            .should("equal", "Please include an '@' in the email address. 'email' is missing an '@'.");
    });

    it('Displays email not verify', () => {
        cy.exec('php artisan migrate:refresh --seed', { timeout: 600000 });
        cy.visit('/register');
        cy.get('[data-id="fullname_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('user name')
            .blur();
        cy.get('[data-id="email_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('email@gmail.com')
            .blur();
        cy.get('[data-id="password_register"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="password_confirmation"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="register"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="h1-verification"]')
            .should('have.text', 'Verification Needed');
    });
})

Cypress.Commands.add('getIframeBody', () => {
    return cy.get('iframe#preview-html')
        .its('0.contentDocument.body')
        .should('not.be.empty')
        .then(cy.wrap);
})
