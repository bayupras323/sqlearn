describe('Testing Create Package', () => {

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
    it('Can Create Practice Package', () => {
        cy.get('[data-id="tambah_paket"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="nama_paket"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Latihan Soal ERD Relasi TI-2A')
            .blur();
        cy.get('[data-id="jenis_topik"]')
            .select('2', { force: true });
        cy.get('[data-id="button_submit"]')
            .should('be.visible')
            .click();
        cy.get('[data-id=success_alert]')
            .should('contain', 'Paket Soal Berhasil Ditambahkan');
    });

    // Negative Case
    it('Name Wajib Diisi', () => {
        cy.get('[data-id="tambah_paket"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="jenis_topik"]')
            .select('2', { force: true });
        cy.get('[data-id="button_submit"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="tambah_paket"]')
            .should('be.visible')
            .click({ force: true });
        cy.get('[data-id="invalid_name_tambah_packages"]')
            .should('contain', 'Nama Paket Soal wajib diisi');
    });

    it('Topik Wajib Diisi', () => {
        cy.get('[data-id="tambah_paket"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="nama_paket"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Latihan Soal ERD Relasi TI-2I')
            .blur();
        cy.get('[data-id="button_submit"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="tambah_paket"]')
            .should('be.visible')
            .click({ force: true });
        cy.get('[data-id="invalid_topik_tambah_packages"]')
            .should('contain', 'Topik Wajib Di isi');
    });
});

