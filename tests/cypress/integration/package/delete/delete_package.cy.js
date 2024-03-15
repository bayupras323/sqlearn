describe('Testing Delete Package', () => {

    before(() => {
        // Reset Database
        cy.exec('php artisan migrate:refresh --seed', { timeout: 600000 });
    });

    beforeEach(() => {
        // Login as Lecturer
        // Click Menu Manajemen Paket Soal
        // Click Menu Data Paket Soal
        // Go to Practice Package Page
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
        cy.get('[data-id=menu-6]')
            .should('be.visible')
            .click();
        cy.get('[data-id=menu-6-10]')
            .should('be.visible')
            .click();
    });

    // Positive Case
    it('Can Delete this Practice Package', () => {
        cy.get('[data-id="delete_paket_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id=success_alert]')
            .should('contain', 'Paket Berhasil Dihapus ');
    });
});