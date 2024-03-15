describe('Testing Read Mahasiswa Page', () => {

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
    it('Lecturer can see list of students', () => {
        cy.get('[data-id="kolom"')
            .should('contain', '#');
        cy.get('[data-id="kolom_nim"]')
            .should('contain', '1');
    });

    // Negative Case
    it('Lecturer cannot see list of students', () => {
        cy.exec('php artisan migrate:refresh --path=/database/migrations/2023_02_13_105842_create_students_table.php', { timeout: 600000 });
        cy.get('[data-id="data_none"]')
            .should('contain', 'Tidak ada data untuk ditampilkan');
    });
});