describe('Read Kelas', () => {

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
    it('Read class successfully', () => {
        cy.get('[data-id=data_kelas_1]')
            .should('contain', 'TI-1A');
    });

    // Negative Case
    it('Read class unsuccessfully', () => {
        cy.visit('/classrooms?classroom-keyword=Z');
        cy.get('[data-id=data_kelas_kosong]')
            .should('contain', 'Tidak ada data untuk ditampilkan');
    });
});