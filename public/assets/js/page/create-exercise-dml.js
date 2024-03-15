// show table list by database
$('#database').on('change', function () {
    // get db_name & db_id
    var db_name = $(this).val();
    var db_id = $(this).find('option:selected').data('id');

    // set db_name & db_id for next step
    $('#db_name').html(db_name);
    $('#database_id').val(db_id);

    // remove error message
    $('#database + span').removeClass('is-invalid')
    $('#invalidDatabase').addClass('d-none')

    // get table list from selected database
    if (db_name) {
        $.ajax({
            url: '/packages/exercises/getTableList/' + db_name,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                const container = $('#accordion');
                container.empty();

                generateAccordionTableList(container, data);
            }
        });
    } else {
        $('#accordion').empty();
    }
});

// show table list
function generateAccordionTableList(container, accordionData) {
    accordionData = JSON.parse(accordionData);
    accordionData.forEach(data => {
        // create accordion element
        const accordion = $('<div></div>').addClass('accordion').appendTo(container);

        // create header element
        const header = $('<div></div>').addClass('d-flex').appendTo(accordion);

        // create checkbox element
        const checkbox = $('<input>').attr('type', 'checkbox').addClass('myCheckbox')
            .attr('value', data.name).attr('name', 'table').appendTo(header);

        // create title element
        const title = $('<div></div>').addClass('accordion-header flex-fill ml-2')
            .attr('role', 'button').attr('data-toggle', 'collapse')
            .attr('data-target', '#collapse' + data.name)
            .text(data.name + ` (${data.dataCount} data record)`).appendTo(header);

        // create content element
        const content = $('<div></div>').addClass('accordion-body collapse ml-2')
            .attr('id', 'collapse' + data.name).attr('data-parent', '#accordion').appendTo(accordion);

        generateTableDesc(content, data)
    });
}

// show selected table list
function generateAccordionTableData(container, accordionData) {
    // accordionData = JSON.parse(accordionData);
    accordionData.forEach(data => {
        // create accordion element
        const accordion = $('<div></div>').addClass('accordion').appendTo(container);

        // create header element
        const header = $('<div></div>').addClass('d-flex').appendTo(accordion);

        // create title element
        const title = $('<div></div>').addClass('accordion-header flex-fill ml-2')
            .attr('role', 'button').attr('data-toggle', 'collapse')
            .attr('data-target', '#collapse' + data.name)
            .text(data.name).appendTo(header);

        // create content element
        const content = $('<div></div>').addClass('accordion-body collapse ml-2')
            .attr('id', 'collapse' + data.name).attr('data-parent', '#accordion').appendTo(accordion);

        generateTableData(content, data)
    });
}

// show table desc
function generateTableDesc(container, tableData) {
    // create responsive cover
    const responsive = $('<div></div>').addClass('table-responsive').appendTo(container);

    // create table element
    const table = $('<table></table>').addClass('table table-sm table-bordered').appendTo(responsive);
    const headers = ['Nama Kolom', 'Tipe Data', 'Key']

    // create table header
    const thead = $('<thead></thead>');
    const tr = $('<tr></tr>');
    headers.forEach(header => {
        const th = $('<th></th>').text(header);
        tr.append(th);
    });
    thead.append(tr);

    // create table body
    const tbody = $('<tbody></tbody>');
    tableData.columns.forEach(row => {
        const tr = $('<tr></tr>');
        const name = $('<td></td>').text(row.name);
        const type = $('<td></td>').text(row.type);
        const key = $('<td></td>').text(row.key);
        tr.append(name);
        tr.append(type);
        tr.append(key);
        tbody.append(tr);
    });

    // add header and body to table
    table.append(thead);
    table.append(tbody);

    // add table to container
    container.append(table);
}

// show table data
function generateTableData(container, tableData) {
    // create responsive cover
    const responsive = $('<div></div>').addClass('table-responsive').appendTo(container);

    // create table element
    const table = $('<table></table>').addClass('table table-sm table-bordered').appendTo(responsive);

    // create table header
    const thead = $('<thead></thead>').appendTo(table);
    const tr = $('<tr></tr>').appendTo(thead);
    tableData.columns.forEach(column => {
        $('<th></th>').text(column.name).appendTo(tr);
    });

    // create table body
    const tbody = $('<tbody></tbody>').appendTo(table);
    tableData.data.forEach(row => {
        const tr = $('<tr></tr>').appendTo(tbody);
        tableData.columns.forEach(column => {
            $('<td></td>').text(row[column.name]).appendTo(tr);
        });
    });
}

// initiation stepper
var stepper = new Stepper(document.getElementById('stepper'), {
    linear: true,
    animation: true,
});

$('.bs-stepper-header > .step.active > .step-trigger > .wizard-step').addClass('wizard-step-active');

// next step
// selected tables to be used in executeQuery()
var selectedTables = [];
function nextStep() {
    if ($('#database_id').val() != '' && numberOfChecked != 0) {
        var numberOfChecked = $('input[name=table][type=checkbox]:checked').length;
        if (numberOfChecked != 0) {
            $('.wizard-step-active').removeClass('wizard-step-active');
            stepper.next();
            let currentStep = $('.bs-stepper-header > .step.active > .step-trigger > .wizard-step');
            currentStep.addClass('wizard-step-active');

            const container = $('#accordion_selected');
            container.empty();
            $('input[type=checkbox]:checked').each(function () {
                let value = $(this).val()
                if (value != 'on') {
                    selectedTables.push($(this).val())
                    $.ajax({
                        url: '/packages/exercises/getTableData/' + $('#db_name').text() + '/' + $(this).val(),
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            generateAccordionTableData(container, data);
                        }
                    });
                }
            });
        } else {
            $('#invalidTable').removeClass('d-none')
            $('.accordion-header').addClass('invalid-accordion')
        }
    } else {
        $('#database + span').addClass('is-invalid')
        $('#invalidDatabase').removeClass('d-none')
    }
}

// previous step
function previousStep() {
    $('.wizard-step-active').removeClass('wizard-step-active');
    stepper.previous();
    let currentStep = $('.bs-stepper-header > .step.active > .step-trigger > .wizard-step');
    currentStep.addClass('wizard-step-active');

    selectedTables = []
}

function executeQuery() {
    let data = {
        db_id: $('#database_id').val(),
        selected_tables: JSON.stringify(selectedTables),
        answer: $('#answer').val()
    }

    return new Promise((resolve, reject) => {
        $.ajax({
            url: `/packages/exercises/executeQuery/${data.db_id}/${data.selected_tables}/${data.answer}`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            processData: false,
            contentType: false,
            success: function (response) {

                switch (response.code) {
                    case 200:
                        $('#correctAnswer').removeClass('d-none')
                        $('#correctAnswer').text(response.message)

                        $('#invalidAnswer').addClass('d-none')

                        resolve()

                        break
                    case 406:
                        $('#invalidAnswer').removeClass('d-none')
                        $('#invalidAnswer').html(response.message)

                        $('#correctAnswer').addClass('d-none')

                        reject()

                        break
                    default:
                        reject()

                        break
                }
            },
            error: function (xhr, status, error) {

                var errorMessage = '';
                $.each(xhr.responseJSON.errors, function (key, error) {
                    errorMessage += error[0] + '\n';
                });
                alert(errorMessage);

                reject()
            }
        });
    })
}

// submit form
function submitForm() {
    if ($('#question').val() != '' && $('#answer').val() != '') {
        executeQuery()
            .then(() => {
                $('#create-exercise-form').submit();
            })
    } else {
        $('#invalidQuestion').removeClass('d-none')
        $('#invalidAnswer').removeClass('d-none')
        $('.note-editor').addClass('invalid-summernote')
        $('#answer').addClass('is-invalid')
    }
}