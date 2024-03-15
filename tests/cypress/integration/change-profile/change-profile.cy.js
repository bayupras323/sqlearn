describe('Testing Change Profile', () => {

    before(() => {
        // Reset database
        cy.exec('php artisan migrate:refresh --seed', { timeout: 600000 });
    });

    beforeEach(() => {
        cy.fixture("credentials.json")
            .then(credential => {
                cy.wrap(credential)
                    .as("credential");
            });
        cy.fixture("change-profiles.json")
            .then(data => {
                cy.wrap(data)
                    .as("data");
            });
        cy.get("@credential")
            .then(credential => {
                cy.login(credential.email, credential.password);
                cy.visit('/dashboard');
                cy.get('[data-id="greetings"]')
                    .should('be.visible')
                    .click();
                cy.visit('/edit-profile');
            });
    });

    // Positive Case
    it('Change profile successfuly', () => {
        cy.get("@data")
            .then(data => {
                cy.get('[data-id="name"]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(data.name)
                    .blur();
                cy.get('[data-id="email"]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(data.email)
                    .blur();
                cy.get('[data-id="save"]')
                    .should('be.visible')
                    .click();
                cy.visit('http://localhost:8025/');
                cy.get(':nth-child(1) > .col-sm-4')
                    .should('be.visible')
                    .click();
                cy.getIframeBody()
                    .find('a')
                    .contains('Verify Email Address')
                    .should('be.visible')
                    .click();
                cy.visit('/dashboard');
                cy.get('[data-id="greetings"]')
                    .should('contain', data.name)
                    .should('be.visible')
                    .click();
                cy.visit('/edit-profile');
                cy.get('[data-id="name"]')
                    .should('have.value', data.name);
                cy.get('[data-id="email"]')
                    .should('have.value', data.email);
            });
    });

    // Negative Case
    it('Cannot change profile when all fields is empty', () => {
        cy.get('[data-id="name"]')
            .focus()
            .should('be.visible')
            .clear()
            .blur();
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .blur();
        cy.get('[data-id="save"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="name-error-message"]')
            .should('contain', 'The name field is required.');
        cy.get('[data-id="email-error-message"]')
            .should('contain', 'The email field is required.');
    });

    it('Cannot change profile when name is empty', () => {
        cy.get('[data-id="name"]')
            .focus()
            .should('be.visible')
            .clear()
            .blur();
        cy.get('[data-id="save"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="name-error-message"]')
            .should('contain', 'The name field is required.');
    });

    it('Cannot change profile when email is empty', () => {
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .blur();
        cy.get('[data-id="save"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="email-error-message"]')
            .should('contain', 'The email field is required.');
    });

    it('Cannot change profile when email is invalid', () => {
        cy.get("@data")
            .then(data => {
                cy.get('[data-id="email"]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(data.invalidEmail)
                    .blur();
            });
        cy.get('[data-id="save"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="email-error-message"]')
            .should('contain', 'The email must be a valid email address.');
    });

    it('Cannot change profile when email is exist', () => {
        cy.get("@data")
            .then(data => {
                cy.get('[data-id="email"]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(data.existingEmail)
                    .blur();
            });
        cy.get('[data-id="save"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="email"]')
            .should('have.class', 'is-invalid');
        cy.get('[data-id="email-error-message"]')
            .should('contain', 'The email has already been taken.');
    });
});

Cypress.Commands.add('login', (email, password) => {
    cy.session([email, password], () => {
        cy.visit('/login');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type(email)
            .blur();
        cy.get('[data-id="password"]')
            .focus()
            .should('be.visible')
            .clear()
            .type(password)
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
        cy.url()
            .should('contain', '/dashboard');
    });
});

Cypress.Commands.add('getIframeBody', () => {
    return cy.get('iframe#preview-html')
        .its('0.contentDocument.body')
        .should('not.be.empty')
        .then(cy.wrap);
});
