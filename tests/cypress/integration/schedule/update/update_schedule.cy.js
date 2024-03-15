describe('Testing Create Schedule', () => {

    before(() => {
        // Reset Database
        cy.exec('php artisan migrate:refresh --seed', { timeout: 600000 });
    });

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
    it('Can edit schedule successfully', () => {
        cy.get('[data-id=edit_jadwal_1]')
            .should('be.visible')
            .click('');
        cy.get('[data-id=name_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Ujian ERD Entity dan Atribut Edited')
            .blur();
        cy.get('[data-id=tipe_edit_jadwal_1]')
            .select('exam', { force: true });
        cy.get('[data-id=start_date_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-02T01:14')
            .blur();
        cy.get('[data-id=end_date_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-04-02T08:00')
            .blur();
        cy.get('[data-id=kelas_edit_jadwal_1]')
            .select('1', { force: true });
        cy.get('[data-id=package_edit_jadwal_1]')
            .select('2', { force: true });
        cy.get('[data-id=edit_jadwal_simpan_1]')
            .should('be.visible')
            .click();
        cy.get('[data-id=success_alert]')
            .should('contain', 'Jadwal Berhasil Diubah');
    });

    // Negative Case
    it('Cannot edit schedule because name field is empty', () => {
        cy.get('[data-id=edit_jadwal_1]')
            .should('be.visible')
            .click('');
        cy.get('[data-id=name_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear();
        cy.get('[data-id=tipe_edit_jadwal_1]')
            .select('exam');
        cy.get('[data-id=start_date_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-02T10:00')
            .blur();
        cy.get('[data-id=end_date_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-04-02T08:00')
            .blur();
        cy.get('[data-id=kelas_edit_jadwal_1]')
            .select('1', { force: true });
        cy.get('[data-id=package_edit_jadwal_1]')
            .select('2', { force: true });
        cy.get('[data-id=edit_jadwal_simpan_1]')
            .should('be.visible')
            .click();
        cy.get('[data-id=invalid_name_edit_jadwal_1]')
            .should('contain', 'Nama Jadwal wajib diisi');
    });

    it('Cannot edit schedule because start date field is empty', () => {
        cy.get('[data-id=edit_jadwal_1]')
            .should('be.visible')
            .click('');
        cy.get('[data-id=name_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Ujian ERD Entity dan Atribut Edited')
            .blur();
        cy.get('[data-id=tipe_edit_jadwal_1]')
            .select('exam', { force: true });
        cy.get('[data-id=start_date_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear();
        cy.get('[data-id=end_date_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-04-02T08:00');
        cy.get('[data-id=kelas_edit_jadwal_1]')
            .select('1', { force: true });
        cy.get('[data-id=package_edit_jadwal_1]')
            .select('2', { force: true });
        cy.get('[data-id=edit_jadwal_simpan_1]')
            .should('be.visible')
            .click();
        cy.get('[data-id=invalid_start_date_edit_jadwal_1]')
            .should('contain', 'Waktu mulai wajib diisi');
    });

    it('Cannot edit schedule because end date field is empty', () => {
        cy.get('[data-id=edit_jadwal_1]')
            .should('be.visible')
            .click();
        cy.get('[data-id=name_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Ujian ERD Entity dan Atribut Edited')
            .blur();
        cy.get('[data-id=tipe_edit_jadwal_1]')
            .select('exam');
        cy.get('[data-id=start_date_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-02T10:00')
            .blur();
        cy.get('[data-id=end_date_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear();
        cy.get('[data-id=kelas_edit_jadwal_1]')
            .select('1', { force: true });
        cy.get('[data-id=package_edit_jadwal_1]')
            .select('2', { force: true });
        cy.get('[data-id=edit_jadwal_simpan_1]')
            .should('be.visible')
            .click();
        cy.get('[data-id=invalid_end_date_edit_jadwal_1]')
            .should('contain', 'Waktu berakhir wajib diisi');
    });

    it('Cannot edit schedule because end date field s value is before start date field s value', () => {
        cy.get('[data-id=edit_jadwal_1]')
            .should('be.visible')
            .click('');
        cy.get('[data-id=name_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Ujian ERD Entity dan Atribut Edited')
            .blur();
        cy.get('[data-id=tipe_edit_jadwal_1]')
            .select('exam', { force: true });
        cy.get('[data-id=start_date_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-02T10:00')
            .blur();
        cy.get('[data-id=end_date_edit_jadwal_1]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-02-28T09:00')
            .blur();
        cy.get('[data-id=kelas_edit_jadwal_1]')
            .select('1', { force: true });
        cy.get('[data-id=package_edit_jadwal_1]')
            .select('2', { force: true });
        cy.get('[data-id=edit_jadwal_simpan_1]')
            .should('be.visible')
            .click();
        cy.get('[data-id=invalid_end_date_edit_jadwal_1]')
            .should('contain', 'Waktu berakhir harus setelah waktu mulai');
    });
});