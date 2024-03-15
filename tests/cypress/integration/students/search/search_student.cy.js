describe('Testing Search Mahasiswa Page', () => {

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
    it('Lecturer can search student by name', () => {
        cy.get('[data-id="cari_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="cari_nama"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('student A')
            .blur();
        cy.get('[data-id="submit_cari"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="kolom_nama"]')
            .should('contain', 'Student A');
    });

    it('Lecturer can search student by nim', () => {
        cy.get('[data-id="cari_mahasiswa"]')
            .should('be.visible').click();
        cy.get('[data-id="cari_nim"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2311110001')
            .blur();
        cy.get('[data-id="submit_cari"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="kolom_nim"]')
            .should('contain', '2311110001');
    });

    // Negative Case
    it('Lecturer cannot search student if search field is empty', () => {
        cy.get('[data-id="cari_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="submit_cari"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="kolom_nim"]')
            .should('contain', '2311110001');
    });
});