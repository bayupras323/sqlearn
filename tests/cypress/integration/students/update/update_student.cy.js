describe('Testing Update Mahasiswa Page', () => {

    before(() => {
        // Reset database
        cy.exec('php artisan migrate:refresh --seed', { timeout: 600000 });
    });

    beforeEach(() => {
        // Login as Lecturer
        // Click Kelas Menu
        // Click Data Kelas Menu
        // Click Lihat Mahasiswa
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
        cy.get('[data-id=menu-5]')
            .should('be.visible')
            .click();
        cy.get('[data-id=menu-5-9]')
            .should('be.visible')
            .click();
        cy.get('[data-id=lihat_mahasiswa_1]')
            .should('be.visible')
            .click();
    });

    // Positive Case
    it('Lecturer can update data nim and name', () => {
        cy.get('[data-id="edit_mahasiswa_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="edit_nim_1"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('1941720159')
            .blur();
        cy.get('[data-id="edit_nama_1"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Student K')
            .blur();
        cy.get('[data-id="ubah_mahasiswa_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="success_alert"]')
            .should('contain', 'Mahasiswa Berhasil diupdate');
    });

    // Negative Case
    it('Lecturer cannot update data if nim is empty and valid name', () => {
        cy.get('[data-id="edit_mahasiswa_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="edit_nim_1"]')
            .focus()
            .should('be.visible')
            .clear()
            .blur();
        cy.get('[data-id="edit_nama_1"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Student A')
            .blur();
        cy.get('[data-id="ubah_mahasiswa_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="edit_invalid_nim"]')
            .should('contain', 'NIM wajib diisi');
    });

    it('Lecturer cannot update data with valid nim and empty name', () => {
        cy.get('[data-id="edit_mahasiswa_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="edit_nim_1"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('1941720119')
            .blur();
        cy.get('[data-id="edit_nama_1"]')
            .focus()
            .should('be.visible')
            .clear()
            .blur();
        cy.get('[data-id="ubah_mahasiswa_1"]')
            .should('be.visible').click();
        cy.get('[data-id="edit_invalid_name"]')
            .should('contain', 'Mahasiswa wajib diisi');
    });

    it('Lecturer cannot update data with valid nim and invalid name', () => {
        cy.get('[data-id="edit_mahasiswa_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="edit_nim_1"]')
            .focus()
            .should('be.visible')
            .click()
            .type('1941720118')
            .blur();
        cy.get('[data-id="edit_nama_1"]')
            .focus()
            .should('be.visible')
            .click()
            .type('08123926453')
            .blur();
        cy.get('[data-id="ubah_mahasiswa_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="edit_invalid_name"]')
            .should('contain', 'Attribute Nama tidak boleh berisi karakter dan angka');
    });

    it('Lecturer cannot update nim if under 10 characters and valid name', () => {
        cy.get('[data-id="edit_mahasiswa_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="edit_nim_1"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('19417201')
            .blur();
        cy.get('[data-id="edit_nama_1"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Nabilasw1sc')
            .blur();
        cy.get('[data-id="ubah_mahasiswa_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="add_invalid_nim"]')
            .should('contain', 'NIM harus 10 karakter angka');
    });

    it('Lecturer cannot add nim if under 10 characters and invalid name', () => {
        cy.get('[data-id="edit_mahasiswa_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="edit_nim_1"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('19417204')
            .blur();
        cy.get('[data-id="edit_nama_1"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('089131344124')
            .blur();
        cy.get('[data-id="ubah_mahasiswa_1"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="add_invalid_nim"]')
            .should('contain', 'NIM harus 10 karakter angka')
    });
});

