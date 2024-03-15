describe('Testing Show Scores of corresponding Classroom and Schedule ', () => {

    beforeEach(() => {
        cy.fixture("credentials.json")
            .then(credential => {
                cy.wrap(credential)
                    .as("credential");
            });

        cy.get("@credential")
            .then(credential => {
                cy.login(credential.emailLecturer, credential.password)
                cy.visit('/dashboard');
                cy.visit('/scores');
            });
    });

    // Positive Case
    it('Can show score list of scores of corresponding classroom and schedule', () => {
        cy.get('[data-id="show_score_button_1"]').click();
        cy.get('[data-id="score_1"]').should('contain', '1');
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