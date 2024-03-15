describe('Create Kelas', () => {

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
    it('Add class successfully without daftar mahasiswa', () => {
        cy.get('[data-id=tambah_kelas]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_nama_kelas]')
            .focus()
            .should('be.visible')
            .clear()
            .type('TI-2A')
            .blur();
        cy.get('[data-id=tambah_kelas_semester]')
            .select('4', { force: true });
        cy.get('[data-id=tambah_kelas_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=success_alert]')
            .should('contain', 'Kelas berhasil ditambahkan');
    });

    it('Add class successfully with daftar mahasiswa', () => {
        const fileName = 'sqlearn_list_of_students_format.xlsx';
        cy.get('[data-id=tambah_kelas]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_nama_kelas]')
            .focus()
            .should('be.visible')
            .clear()
            .type('TI-2B')
            .blur();
        cy.get('[data-id=tambah_kelas_semester]')
            .select('4', { force: true });
        cy.fixture(fileName, 'binary')
            .then(Cypress.Blob.binaryStringToBlob)
            .then(fileContent => {
                cy.get('[data-id=tambah_kelas_file')
                    .attachFile({ fileContent, fileName, mimeType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', encoding: 'utf8' })
            })
        cy.get('[data-id=tambah_kelas_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=success_alert]')
            .should('contain', 'Kelas berhasil ditambahkan');
    });

    // Negative Case
    it('Cannot add class because all fields are empty', () => {
        cy.get('[data-id=tambah_kelas]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_kelas_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_kelas_nama_error]')
            .should('contain', 'Nama Kelas wajib diisi');
        cy.get('[data-id=tambah_kelas_semester_error]')
            .should('contain', 'Semester wajib diisi');
    });

    it('Cannot add class because nama kelas is empty', () => {
        cy.get('[data-id=tambah_kelas]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_kelas_semester]')
            .select('8', { force: true });
        cy.get('[data-id=tambah_kelas_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_kelas_nama_error]')
            .should('contain', 'Nama Kelas wajib diisi');
    });

    it('Cannot add class because nama kelas is already taken', () => {
        cy.get('[data-id=tambah_kelas]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_nama_kelas]')
            .focus()
            .should('be.visible')
            .clear()
            .type('TI-2A')
            .blur();
        cy.get('[data-id=tambah_kelas_semester]')
            .select('4', { force: true });
        cy.get('[data-id=tambah_kelas_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_kelas_nama_error]')
            .should('contain', 'Kelas dengan nama TI-2A sudah terdaftar');
    });

    it('Cannot add class because nama kelas is less than 5 characters', () => {
        cy.get('[data-id=tambah_kelas]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_nama_kelas]')
            .focus()
            .should('be.visible')
            .clear()
            .type('TI')
            .blur();
        cy.get('[data-id=tambah_kelas_semester]')
            .select('8', { force: true });
        cy.get('[data-id=tambah_kelas_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_kelas_nama_error]')
            .should('contain', 'Nama Kelas harus 5-6 karakter');
    });

    it('Cannot add class because nama kelas format is invalid', () => {
        cy.get('[data-id=tambah_kelas]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_nama_kelas]')
            .focus()
            .should('be.visible')
            .clear()
            .type('TI2AB')
            .blur();
        cy.get('[data-id=tambah_kelas_semester]')
            .select('8', { force: true });
        cy.get('[data-id=tambah_kelas_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_kelas_nama_error]')
            .should('contain', 'Nama Kelas harus sesuai format TI/SIB-TingkatKelas (misal: TI-1A atau SIB-2C)');
    });

    it('Cannot add class because semester is empty', () => {
        cy.get('[data-id=tambah_kelas]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_nama_kelas]')
            .focus()
            .should('be.visible')
            .clear()
            .type('TI-4A')
            .blur();
        cy.get('[data-id=tambah_kelas_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_kelas_semester_error]')
            .should('contain', 'Semester wajib diisi');
    });

    it('Cannot add class because daftar mahasiswa file format is invalid', () => {
        const fileNamePdf = 'sqlearn_list_of_students_format.pdf';
        cy.get('[data-id=tambah_kelas]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_nama_kelas]')
            .focus()
            .should('be.visible')
            .clear()
            .type('TI-4A')
            .blur();
        cy.get('[data-id=tambah_kelas_semester]')
            .select('8', { force: true });
        cy.get('[data-id=tambah_kelas_file')
            .attachFile(fileNamePdf)
        cy.get('[data-id=tambah_kelas_simpan]')
            .should('be.visible')
            .click();
        cy.get('[data-id=tambah_kelas_file_error]')
            .should('contain', 'Format file Daftar Mahasiswa harus berkestensi .xls, .xlsx, atau .csv');
    });
});