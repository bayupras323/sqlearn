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
    it('Can search schedule with data successfully', () => {
        cy.get('[data-id=cari_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=cari_jadwal_input]')
            .focus()
            .should('be.visible')
            .clear()
            .type('ujian')
            .blur();
        cy.get('[data-id=cari_jadwal_submit]')
            .should('be.visible')
            .click();
        cy.get('[data-id=schedule_1]')
            .should('contain', '1');
    });

    // Negative Case
    it('Search schedule cannot find data', () => {
        cy.get('[data-id=cari_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=cari_jadwal_input]')
            .focus()
            .should('be.visible')
            .clear()
            .type('xyz')
            .blur();
        cy.get('[data-id=cari_jadwal_submit]')
            .should('be.visible')
            .click();
        cy.get('[data-id=data_jadwal_kosong]')
            .should('contain', 'Tidak ada data untuk ditampilkan');
    });
});