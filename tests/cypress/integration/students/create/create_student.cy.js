describe('Testing Create Mahasiswa Page', () => {

    before(() => {
        // Reset Database
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
    it('Lecturer can add valid nim and valid name', () => {
        cy.get('[data-id="tambah_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="nim_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('1941720147')
            .blur();
        cy.get('[data-id="nama_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Senja')
            .blur();
        cy.get('[data-id="save_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="success_alert"]')
            .should('contain', 'Mahasiswa Berhasil ditambahkan ');
    });
    
    // Negative Case
    it('Lecturer can add valid nim and invalid name', () => {
        cy.get('[data-id="tambah_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="nim_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('1941720152')
            .blur();
        cy.get('[data-id="nama_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('089131344124')
            .blur();
        cy.get('[data-id="save_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="add_invalid_nama"]')
            .should('contain', 'Nama tidak boleh berisi karakter dan angka');
    });

    it('Lecturer can add invalid nim and valid name', () => {
        cy.get('[data-id="tambah_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="nim_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Ini nim')
            .blur();
        cy.get('[data-id="nama_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('valid name')
            .blur();
        cy.get('[data-id="save_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="add_invalid_nim"]')
            .should('contain', 'Masukkan NIM dalam bentuk angka');
    });

    it('Lecturer can add invalid nim and invalid name', () => {
        cy.get('[data-id="tambah_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="nim_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('invalidnim')
            .blur();
        cy.get('[data-id="nama_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('089132842356')
            .blur();
        cy.get('[data-id="save_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="add_invalid_nim"]')
            .should('contain', 'Masukkan NIM dalam bentuk angka');

    });

    it('Lecturer can add nim if already taken and valid name', () => {
        cy.get('[data-id="tambah_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="nim_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('1941720134')
            .blur();
        cy.get('[data-id="nama_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Valid Name')
            .blur();
        cy.get('[data-id="save_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="add_invalid_nim"]')
            .should('contain', 'NIM telah terdaftar');
    });

    it('Lecturer can add nim if already taken and invalid name', () => {
        cy.get('[data-id="tambah_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="nim_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('1941720134')
            .blur();
        cy.get('[data-id="nama_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('08912463253')
            .blur();
        cy.get('[data-id="save_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="add_invalid_nim"]')
            .should('contain', 'NIM telah terdaftar');
    });

    it('Lecturer can add empty nim and empty name', () => {
        cy.get('[data-id="tambah_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="save_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="add_invalid_nim"]')
            .should('contain', 'NIM wajib diisi');
        cy.get('[data-id="add_invalid_nama"]')
            .should('contain', 'Nama Mahasiswa wajib diisi');
    });

    it('Lecturer can add empty nim and valid name', () => {
        cy.get('[data-id="tambah_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="nama_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Dina')
            .blur();
        cy.get('[data-id="save_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="add_invalid_nim"]')
            .should('contain', 'NIM wajib diisi');
    });

    it('Lecturer can add valid nim and empty name', () => {
        cy.get('[data-id="tambah_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="nim_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('1941720173')
            .blur();
        cy.get('[data-id="save_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="add_invalid_nama"]')
            .should('contain', 'Mahasiswa wajib diisi');
    });

    it('Lecturer cannot add nim if under 10 characters and valid name', () => {
        cy.get('[data-id="tambah_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="nim_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('19417201')
            .blur();
        cy.get('[data-id="nama_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('Nabilasw1sc')
            .blur();
        cy.get('[data-id="save_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="add_invalid_nim"]')
            .should('contain', 'NIM harus 10 karakter angka');
    });

    it('Lecturer cannot add nim if under 10 characters and invalid name', () => {
        cy.get('[data-id="tambah_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="nim_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('19417204')
            .blur();
        cy.get('[data-id="nama_mahasiswa"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('089131344124')
            .blur();
        cy.get('[data-id="save_mahasiswa"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="add_invalid_nim"]')
            .should('contain', 'NIM harus 10 karakter angka')
    });
});