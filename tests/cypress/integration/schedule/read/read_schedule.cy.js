describe('Testing Create Schedule', () => {

    beforeEach(() => {
        // Login as Lecturer
        // Click Schedule Menu
        // Click Data Schedule Menu
        // Go to Schedule Page
        cy.visit('/');
        cy.get('[data-id=email]')
            .focus()
            .should('be.visible')
            .clear()
            .type('lecturer@gmail.com')
            .blur();
        cy.get('[data-id=password]')
            .focus()
            .should('be.visible')
            .clear()
            .type('password')
            .blur();
        cy.get('[data-id=login]')
            .should('be.visible')
            .click();
        cy.get('[data-id="greetings"]')
            .should('have.text', 'Hi, lecturer');
        cy.get('[data-id=sidebar]')
            .should('be.visible')
            .click();
        cy.get('[data-id=menu-7]')
            .should('be.visible')
            .click();
        cy.get('[data-id=menu-7-11]')
            .should('be.visible')
            .click();
    });

    // Positive Case
    it('Can show schedules data', () => {
        cy.get('[data-id=schedule_1]')
            .should('contain', '1');
    });

    it('Can show class detail in a schedule data', () => {
        cy.get('[data-id=detail_jadwal_1]')
            .click();
        cy.get('[data-id=detail_kelas]')
            .should('contain', 'TI-1A');
    });
});