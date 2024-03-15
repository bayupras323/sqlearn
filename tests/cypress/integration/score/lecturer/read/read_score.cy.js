describe('Testing Read Schedules with Scores', () => {

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
    it('Can show all complete and in-progress schedules with scores', () => {
        cy.get('[data-id="schedule_1"]').should('contain', '1');
    });

    // Negative Case
    it('Show an empty message if there are no complete and in-progress schedules with scores', () => {
        cy.visit('/scores?schedule-keyword=Z');
        cy.get('[data-id=data_nilai_kosong]')
            .should('contain', 'Tidak ada nilai latihan untuk ditampilkan');
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