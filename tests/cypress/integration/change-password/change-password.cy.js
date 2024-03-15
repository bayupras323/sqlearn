describe('Testing Change Password in Profile Page', () => {

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

        cy.get("@credential")
            .then(credential => {
                cy.login(credential.email, credential.password)
                cy.visit('/dashboard');
                cy.get('[data-id="greetings"]')
                    .should('be.visible')
                    .click();
                cy.get('[data-id="navbar_profile_item"]')
                    .should('be.visible')
                    .click();
            });
    })

    // Positive Case
    it('Change password successfuly', () => {
        cy.get("@credential")
            .then(credential => {
                cy.get('[data-id=current_password]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=new_password]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=password_confirmation]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=button_change_password]')
                    .should('be.visible')
                    .click();
                cy.get('[data-id=current_password]')
                    .should('be.visible');
            });
    });

    // Negative Case
    it('All fields empty', () => {
        cy.get('[data-id=button_change_password]')
            .should('be.visible')
            .click();
        cy.get('[data-id=current_password]')
            .should('have.class', 'is-invalid');
        cy.get('[data-id=new_password]')
            .should('have.class', 'is-invalid');
    });

    it('Password Confirmation field empty', () => {
        cy.get("@credential")
            .then(credential => {
                cy.get('[data-id=current_password]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=new_password]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=button_change_password]')
                    .should('be.visible')
                    .click();
                cy.get('.invalid-feedback')
                    .should('contain', 'The password confirmation does not match.');
            });
    });

    it('New Password field empty', () => {
        cy.get("@credential")
            .then(credential => {
                cy.get('[data-id=current_password]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=password_confirmation]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=button_change_password]')
                    .should('be.visible')
                    .click();
                cy.get('.invalid-feedback')
                    .should('contain', 'The password field is required.');
            });
    });

    it('Current Password field empty', () => {
        cy.get("@credential")
            .then(credential => {
                cy.get('[data-id=new_password]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=password_confirmation]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=button_change_password]')
                    .should('be.visible')
                    .click();
                cy.get('.invalid-feedback')
                    .should('contain', 'The current password field is required.');
            });
    });

    it('Current Password invalid', () => {
        cy.get("@credential")
            .then(credential => {
                cy.get('[data-id=current_password]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type('12345678')
                    .blur();
                cy.get('[data-id=new_password]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=password_confirmation]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=button_change_password]')
                    .should('be.visible')
                    .click();
                cy.get('.invalid-feedback')
                    .should('contain', 'The provided password does not match your current password.');
            });
    });

    it('New Password less than 8 characters', () => {
        cy.get("@credential")
            .then(credential => {
                cy.get('[data-id=current_password]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=new_password]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type('8765432')
                    .blur();
                cy.get('[data-id=password_confirmation]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type('8765432')
                    .blur();
                cy.get('[data-id=button_change_password]')
                    .should('be.visible')
                    .click();
                cy.get('.invalid-feedback')
                    .should('contain', 'The password must be at least 8 characters.');
            });
    });

    it('Confirm Password does not match with New Password', () => {
        cy.get("@credential")
            .then(credential => {
                cy.get('[data-id=current_password]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=new_password]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type(credential.password)
                    .blur();
                cy.get('[data-id=password_confirmation]')
                    .focus()
                    .should('be.visible')
                    .clear()
                    .type('87654322')
                    .blur();
                cy.get('[data-id=button_change_password]')
                    .should('be.visible')
                    .click();
                cy.get('.invalid-feedback')
                    .should('contain', 'The password confirmation does not match.');
            });
    });
});

Cypress.Commands.add('login', (username, password) => {
    cy.session([username, password], () => {
        cy.visit('/login')
        cy.get('[data-id=email]')
            .focus()
            .should('be.visible')
            .clear()
            .type(username)
            .blur();
        cy.get('[data-id=password]')
            .focus()
            .should('be.visible')
            .clear()
            .type(password)
            .blur();
        cy.get('[data-id=login]')
            .should('be.visible')
            .click();
        cy.url()
            .should('contain', '/dashboard')
    });
});