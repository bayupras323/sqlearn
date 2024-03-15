describe('Testing Update Package', () => {

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
    it('Can Update Practice Package', () => {
        cy.get('[data-id="edit_modal_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="edit_nama_paket_1"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Latihan Soal DDL SQL')
            .blur();
        cy.get('[data-id="edit_paket_simpan_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id=success_alert]')
            .should('contain', 'Paket Soal berhasil diubah ');
    });

    // Negative Case
    it('Can\'t Update Practice Package with Empty Name', () => {
        cy.get('[data-id="edit_modal_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="edit_nama_paket_1"]')
            .focus()
            .should('be.visible')
            .clear()
            .blur();
        cy.get('[data-id="edit_paket_simpan_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="invalid_name_edit_packages"]')
            .should('contain', 'Nama Paket Soal wajib diisi');
    });
});