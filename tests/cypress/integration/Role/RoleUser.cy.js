describe('Role User Level Test', () => {

    before(() => {
        // Reset Database
        cy.exec('php artisan migrate:refresh --seed', { timeout: 600000 });
    });

    beforeEach(() => {
        // Login as user
        cy.visit('/');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('user@gmail.com')
            .blur();
        cy.get('[data-id="password"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('password')
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
    });

    // Positive Case
    it('Login => Check Menu Dashboard => Check Menu User Management => Create User', () => {
        cy.visit('/user-management/user');
        cy.get('[data-id="nav-greetings"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="logout"]')
            .should('be.visible')
            .click();
    });

    it('Login => Check Menu User Management => Import User Random File Type', () => {
        cy.visit('/user-management/user');
        cy.get('[data-id="import-user"]')
            .should('be.visible')
            .click();
        cy.get('#file-upload')
            .click();
        cy.get('[data-id="fix-import-file-user"]')
            .should('be.visible')
            .click();
        cy.visit('/user-management/user');
        cy.get('[data-id="nav-greetings"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="logout"]')
            .should('be.visible')
            .click();
    });

    it('Login => Check Menu User Management => Import User Random Correct Type & Incorrect Content', () => {
        cy.visit('/user-management/user');
        cy.get('[data-id="import-user"]')
            .should('be.visible')
            .click();
        cy.get('#file-upload')
            .click();
        cy.get('[data-id="fix-import-file-user"]')
            .should('be.visible')
            .click();
        cy.visit('/user-management/user');
        cy.get('[data-id="nav-greetings"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="logout"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('aqil@gmail.com')
            .blur();
        cy.get('[data-id="password"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
        cy.get('.invalid-feedback')
            .should('have.text', 'These credentials do not match our records.');
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('abdulloh@gmail.com')
            .blur();
        cy.get('[data-id="password"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('12345678')
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
        cy.get('.invalid-feedback')
            .should('have.text', 'These credentials do not match our records.');
    });

    it('Login => Check Menu User Management => Import User Random Correct Type & Correct Content', () => {
        cy.visit('/user-management/user');
        cy.get('[data-id="import-user"]')
            .should('be.visible')
            .click();
        cy.get('#file-upload')
            .click();
        cy.get('[data-id="fix-import-file-user"]')
            .should('be.visible')
            .click();
        cy.visit('/user-management/user');
        cy.get('[data-id="nav-greetings"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="logout"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="email"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('lecturer@gmail.com')
            .blur();
        cy.get('[data-id="password"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('password')
            .blur();
        cy.get('[data-id="login"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="greetings"]')
            .should('be.visible')
            .should('have.text', 'Hi, lecturer');
    });

    // This case not work in some device dont know why
    it('Login => Check Menu User Management => Export User', () => {
        cy.visit('/user-management/user');
        cy.get('[data-id="export-user"]')
            .should('be.visible')
            .click();
        cy.visit('/user-management/user');
        cy.readFile('cypress/downloads/users.xlsx', { timeout: 25000 })
            .should('contain', 'users');
        cy.get('[data-id="nav-greetings"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="logout"]')
            .should('be.visible')
            .click();
    });

    it('Login => Check Menu User Management => Search User', () => {
        cy.visit('/user-management/user');
        cy.get('[data-id="search-user"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="search-user-by-name"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('user')
            .blur();
        cy.get('[data-id="search-user"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="reset-search-user"]')
            .should('be.visible')
            .click({ force: true });
        cy.get('[data-id="search-user"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="search-user-by-name"]')
            .focus()
            .should('be.visible')
            .clear()
            .type('admin')
            .blur();
        cy.get('[data-id="search-user"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="reset-search-user"]')
            .should('be.visible')
            .click({ force: true });
        cy.visit('/user-management/user');
        cy.get('[data-id="nav-greetings"]')
            .should('be.visible')
            .click();
        cy.get('[data-id="logout"]')
            .should('be.visible')
            .click();
    });
});
