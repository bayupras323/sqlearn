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
    it('Can add schedule successfully', () => {
        cy.get('[data-id=tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=name_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Latihan Soal Test')
            .blur();
        cy.get('[data-id=tipe_tambah_jadwal]')
            .select('Latihan', { force: true });
        cy.get('[data-id=start_date_tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=start_date_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-01T00:00')
            .blur();
        cy.get('[data-id=end_date_tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=end_date_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-30T00:00')
            .blur();
        cy.get('[data-id=kelas_tambah_jadwal]')
            .select('1', { force: true });
        cy.get('[data-id=package_tambah_jadwal]')
            .select('1', { force: true });
        cy.get('[data-id=tambah_jadwal_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=success_alert]')
            .should('contain', 'Jadwal berhasil ditambahkan ');
    });

    // Negative Case
    it('Cannot add schedule because name field is empty', () => {
        cy.get('[data-id=tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tipe_tambah_jadwal]')
            .select('Latihan', { force: true });
        cy.get('[data-id=start_date_tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=start_date_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-01T00:00')
            .blur();
        cy.get('[data-id=end_date_tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=end_date_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-30T00:00')
            .blur();
        cy.get('[data-id=kelas_tambah_jadwal]')
            .select('1', { force: true });
        cy.get('[data-id=package_tambah_jadwal]')
            .select('1', { force: true });
        cy.get('[data-id=tambah_jadwal_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=invalid_name_tambah_jadwal]')
            .should('contain', 'Nama Jadwal wajib diisi');
    });

    it('Cannot add schedule because type field is empty', () => {
        cy.get('[data-id=tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=name_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Latihan Soal Test')
            .blur();
        cy.get('[data-id=start_date_tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=start_date_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-01T00:00')
            .blur();
        cy.get('[data-id=end_date_tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=end_date_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-30T00:00')
            .blur();
        cy.get('[data-id=kelas_tambah_jadwal]')
            .select('1', { force: true });
        cy.get('[data-id=package_tambah_jadwal]')
            .select('1', { force: true });
        cy.get('[data-id=tambah_jadwal_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=invalid_tipe_tambah_jadwal]')
            .should('contain', 'Tipe jadwal wajib dipilih');
    });

    it('Cannot add schedule because start date field is empty', () => {
        cy.get('[data-id=tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=name_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Latihan Soal Test')
            .blur();
        cy.get('[data-id=tipe_tambah_jadwal]')
            .select('Latihan', { force: true });
        cy.get('[data-id=end_date_tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=end_date_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-30T00:00')
            .blur();
        cy.get('[data-id=kelas_tambah_jadwal]')
            .select('1', { force: true });
        cy.get('[data-id=package_tambah_jadwal]')
            .select('1', { force: true });
        cy.get('[data-id=tambah_jadwal_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=invalid_start_date_tambah_jadwal]')
            .should('contain', 'Waktu mulai wajib diisi');
    });

    it('Cannot add schedule because end date field is empty', () => {
        cy.get('[data-id=tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=name_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Latihan Soal Test')
            .blur();
        cy.get('[data-id=tipe_tambah_jadwal]')
            .select('Latihan', { force: true });
        cy.get('[data-id=start_date_tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=start_date_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-01T00:00')
            .blur();
        cy.get('[data-id=kelas_tambah_jadwal]')
            .select('1', { force: true });
        cy.get('[data-id=package_tambah_jadwal]')
            .select('1', { force: true });
        cy.get('[data-id=tambah_jadwal_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=invalid_end_date_tambah_jadwal]')
            .should('contain', 'Waktu berakhir wajib diisi');
    });

    it('Cannot add schedule because package field is empty', () => {
        cy.get('[data-id=tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=name_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Latihan Soal Test')
            .blur();
        cy.get('[data-id=tipe_tambah_jadwal]')
            .select('Latihan', { force: true });
        cy.get('[data-id=start_date_tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=start_date_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-01T00:00')
            .blur();
        cy.get('[data-id=end_date_tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=end_date_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-30T00:00')
            .blur();
        cy.get('[data-id=kelas_tambah_jadwal]')
            .select('1', { force: true });
        cy.get('[data-id=tambah_jadwal_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=invalid_package_tambah_jadwal]')
            .should('contain', 'Paket soal wajib dipilih');
    });

    it('Cannot add schedule because end date fields value is before start date fields value', () => {
        cy.get('[data-id=tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=name_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Latihan Soal Test')
            .blur();
        cy.get('[data-id=tipe_tambah_jadwal]')
            .select('Latihan', { force: true });
        cy.get('[data-id=start_date_tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=start_date_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-30T00:00')
            .blur();
        cy.get('[data-id=end_date_tambah_jadwal]')
            .should('be.visible')
            .click();
        cy.get('[data-id=end_date_tambah_jadwal]')
            .focus()
            .should('be.visible')
            .clear()
            .type('2023-03-01T00:00')
            .blur();
        cy.get('[data-id=kelas_tambah_jadwal]')
            .select('1', { force: true });
        cy.get('[data-id=package_tambah_jadwal]')
            .select('1', { force: true });
        cy.get('[data-id=tambah_jadwal_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=invalid_end_date_tambah_jadwal]')
            .should('contain', 'Waktu berakhir harus setelah waktu mulai');
    });
});