describe('Testing Export Score List to Excel ', () => {

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
    it('Can export score list to excel', () => {
        cy.get('[data-id="show_score_button_1"]').click();
        cy.get('[data-id="export_excel_button"]', { timeout: 15000 }).then(($input) => {
            cy.downloadFile($input.attr('href'), 'cypress/downloads', 'export_student_scores.xlsx')
        });
        cy.readFile('cypress/downloads/export_student_scores.xlsx');
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