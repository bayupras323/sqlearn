describe('Testing Search Package', () => {

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
    it('Can Search Practice Package', () => {
        cy.get('[data-id="cari_paket"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="cari_paket_input"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Latihan Soal ERD Entity dan Atribut')
            .blur();
        cy.get('[data-id="cari_paket_submit"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="package_name_1"]')
            .should('contain', 'Latihan Soal ERD Entity dan Atribut');
    });

    // Negative Case
    it('Can\'t Search Practice Package', () => {
        cy.get('[data-id="cari_paket"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="cari_paket_input"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('zxcvbnm')
            .blur();
        cy.get('[data-id="cari_paket_submit"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="data_paket_kosong"]')
            .should('contain', 'Tidak ada data untuk ditampilkan');
    });
});