describe('Update Kelas', () => {

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
    it('Edit class successfully', () => {
        cy.get('[data-id=edit_kelas_3]')
            .should('be.visible')
            .click();
        cy.get('[data-id=edit_nama_kelas_3]')
            .focus()
            .should('be.visible')
            .clear()
            .type('TI-3A')
            .blur();
        cy.get('[data-id=edit_kelas_semester_3]')
            .select('6', { force: true });
        cy.get('[data-id=edit_kelas_simpan_3]')
            .should('be.visible')
            .click();
        cy.get('[data-id=success_alert]')
            .should('contain', 'Kelas berhasil diubah');
    });

    // Negative Case
    it('Cannot edit class because nama kelas is empty', () => {
        cy.get('[data-id=edit_kelas_3]')
            .should('be.visible')
            .click();
        cy.get('[data-id=edit_nama_kelas_3]')
            .focus()
            .should('be.visible')
            .click()
            .clear();
        cy.get('[data-id=edit_kelas_semester_3]')
            .select('2', { force: true });
        cy.get('[data-id=edit_kelas_simpan_3]')
            .should('be.visible')
            .click();
        cy.get('[data-id=edit_kelas_nama_error_3]')
            .should('contain', 'Nama Kelas wajib diisi');
    });

    it('Cannot edit class because nama kelas is less than 5 characters', () => {
        cy.get('[data-id=edit_kelas_3]')
            .should('be.visible')
            .click();
        cy.get('[data-id=edit_nama_kelas_3]')
            .focus()
            .should('be.visible')
            .clear()
            .type('TI')
            .blur();
        cy.get('[data-id=edit_kelas_semester_3]')
            .select('2', { force: true });
        cy.get('[data-id=edit_kelas_simpan_3]')
            .should('be.visible')
            .click();
        cy.get('[data-id=edit_kelas_nama_error_3]')
            .should('contain', 'Nama Kelas harus 5-6 karakter');
    });

    it('Cannot edit class because nama kelas format is invalid', () => {
        cy.get('[data-id=edit_kelas_3]')
            .should('be.visible')
            .click();
        cy.get('[data-id=edit_nama_kelas_3]')
            .focus()
            .should('be.visible')
            .clear()
            .type('TIABC')
            .blur();
        cy.get('[data-id=edit_kelas_semester_3]')
            .select('2', { force: true });
        cy.get('[data-id=edit_kelas_simpan_3]')
            .should('be.visible')
            .click();
        cy.get('[data-id=edit_kelas_nama_error_3]')
            .should('contain', 'Nama Kelas harus sesuai format TI/SIB-TingkatKelas (misal: TI-1A atau SIB-2C)');
    });
});