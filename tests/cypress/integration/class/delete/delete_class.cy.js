describe('Delete Kelas', () => {

    before(() => {
        // Reset Database
        cy.exec('php artisan migrate:refresh --seed', { timeout: 600000 });
    });
    
    beforeEach(() => {
        // Login as Lecturer
        // Click Kelas Menu
        // Go to Kelas Page
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
        cy.get('[data-id="greetings"]').should('have.text', 'Hi, lecturer');
        cy.get('[data-id=sidebar]')
            .should('be.visible')
            .click();
        cy.get('[data-id=menu-5]')
            .should('be.visible')
            .click();
        cy.get('[data-id=menu-5-9]')
            .should('be.visible')
            .click();
    });

    // Positive Case
    it('Delete class successfully', () => {
        cy.get('[data-id=delete_kelas_confirm_1]')
            .should('be.visible')
            .click();
        cy.get('#fire-modal-1 > .modal-dialog > .modal-content > .modal-footer > .btn-danger')
            .should('be.visible')
            .click();
        cy.get('[data-id=success_alert]')
            .should('contain', 'Kelas Berhasil Dihapus');
    });
});