// show table list by database
$('#database').on('change', function () {
    // get db_name & db_id
    let db_name = $(this).val();
    let db_id = $(this).find('option:selected').data('id');

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
        }).then(data => {
            const container = $('#accordion');
            container.empty();

            generateAccordionTableList(container, data);
        });
    } else {
        $('#accordion').empty();
    }
});

// show table list
const generateAccordionTableList = (container, accordionData) => {
    accordionData = JSON.parse(accordionData);
    accordionData.forEach(data => {
        // create accordion element
        const accordion = $('<div></div>').addClass('accordion').appendTo(container);

        // create header element
        const header = $('<div></div>').addClass('d-flex').appendTo(accordion);

        // create checkbox element
        $('<input>').attr('type', 'radio').addClass('myCheckbox')
            .attr('value', data.name).attr('name', 'table')
            .attr('id', 'table').appendTo(header);

        // create title element
        $('<div></div>').addClass('accordion-header flex-fill ml-2')
            .attr('role', 'button').attr('data-toggle', 'collapse')
            .attr('data-target', '#collapse' + data.name)
            .text(data.name).appendTo(header);

        // create content element
        const content = $('<div></div>').addClass('accordion-body collapse ml-2')
            .attr('id', 'collapse' + data.name).attr('data-parent', '#accordion').appendTo(accordion);

        generateTableDesc(content, data)
    });
};

// show table selected
const generateAccordionSelectedTable = (container, accordionData) => {
    accordionData.forEach(data => {
        // create accordion element
        const accordion = $('<div></div>').addClass('accordion').appendTo(container);

        // create header element
        const header = $('<div></div>').addClass('d-flex').appendTo(accordion);

        // create title element
        $('<div></div>').addClass('accordion-header flex-fill ml-2')
            .attr('role', 'button').attr('data-toggle', 'collapse')
            .attr('data-target', '#collapse' + data.name)
            .text(data.name).appendTo(header);

        // create content element
        const content = $('<div></div>').addClass('accordion-body collapse ml-2')
            .attr('id', 'collapse' + data.name).attr('data-parent', '#accordion').appendTo(accordion);

        generateTableDesc(content, data)
    });
};

// show table desc
const generateTableDesc = (container, tableData) => {
    // create responsive cover
    const responsive = $('<div></div>').addClass('table-responsive').appendTo(container);

    // create table element
    const table = $('<table></table>').addClass('table table-sm table-bordered').appendTo(responsive);
    const headers = ['Nama Kolom', 'Tipe Data', 'Key', 'Nullable', 'Default', 'Extra']

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
        $('<td></td>').text(row.name).appendTo(tr);
        $('<td></td>').text(row.type).appendTo(tr);
        $('<td></td>').text(row.key).appendTo(tr);
        $('<td></td>').text(row.null).appendTo(tr);
        $('<td></td>').text(row.default).appendTo(tr);
        $('<td></td>').text(row.extra).appendTo(tr);
        tbody.append(tr);
    });

    // add header and body to table
    table.append(thead);
    table.append(tbody);

    // add table to container
    container.append(table);
};

// add new column
$(document).ready(function () {
    let i = 0;
    $("#addColumnBtn").click(function () {
        let container = $('#addColumnFormContainer');
        let formGroup = $('<div>', {
            class: 'd-flex justify-content-between mb-2 column-form'
        }).appendTo(container);

        // column name field
        $('<div>', {style: 'width: 16%'}).append(
            $('<input>', {
                type: 'text',
                class: 'form-control',
                name: 'columnName[]',
                placeholder: 'Nama kolom'
            })
        ).appendTo(formGroup)

        // column type field
        let dataType = [
            'CHAR', 'VARCHAR', 'BINARY', 'TINYTEXT', 'TEXT', 'MEDIUMTEXT', 'LONGTEXT', 'ENUM',
            'TINYINT', 'BOOL', 'SMALLINT', 'MEDIUMINT', 'INT', 'BIGINT', 'FLOAT', 'DOUBLE', 'DECIMAL',
            'DATE', 'YEAR', 'TIME', 'DATETIME', 'TIMESTAMP'
        ];
        let options = dataType.map(dt => {
            return $('<option>', {value: dt}).text(dt)[0].outerHTML;
        }).join('');
        $('<div>', {style: 'width: 14%'}).append(
            $('<select>', {
                class: 'form-control select2',
                name: 'dataType[]'
            }).append(
                $('<option>', {
                    selected: true,
                    disabled: true
                }).text('Tipe data'),
                options)
        ).appendTo(formGroup);

        // column size field
        $('<div>', {style: 'width: 12%'}).append(
            $('<input>', {
                type: 'number',
                class: 'form-control',
                name: 'columnSize[]',
                placeholder: 'Size kolom'
            })
        ).appendTo(formGroup);

        // column key field
        $('<div>', {style: 'width: 10%'}).append(
            $('<select>', {
                class: 'form-control select2',
                name: 'columnKey[]'
            }).append(
                $('<option>', {
                    value: '',
                    selected: true,
                }).text('Key'),
                $('<option>', {value: 'PRI'}).text('PRI'),
                $('<option>', {value: 'MUL'}).text('MUL'),
                $('<option>', {value: 'UNI'}).text('UNI'),
            )
        ).appendTo(formGroup);

        // column nullability field
        $('<div>', {style: 'width: 14%'}).append(
            $('<select>', {
                class: 'form-control select2',
                name: 'columnNullability[]'
            }).append(
                $('<option>', {
                    selected: true,
                    disabled: true
                }).text('Nullability'),
                $('<option>', {value: 'YES'}).text('YES'),
                $('<option>', {value: 'NO'}).text('NO'),
            )
        ).appendTo(formGroup);

        // column default field
        $('<div>',{style: 'width: 12%'}).append(
            $('<input>', {
                type: 'text',
                class: 'form-control',
                name: 'columnDefault[]',
                placeholder: 'Default'
            })
        ).appendTo(formGroup);

        // column extra field
        $('<div>', {style: 'width: 15%'}).append(
            $('<select>', {
                class: 'form-control select2',
                name: 'columnExtra[]'
            }).append(
                $('<option>', {
                    value: '',
                    selected: true,
                }).text('Extra'),
                $('<option>', {value: 'AUTO_INCREMENT'}).text('AUTO_INCREMENT')
            )
        ).appendTo(formGroup);

        // drop column action
        $('<div>', {
            style: 'width: 2%',
            class: 'mt-2'
        }).append(
            $('<a>', {href: '#'}).append(
                $('<i>', {
                    class: 'fas fa-times text-danger',
                    style: 'font-size: 16px'
                })
            )
        ).appendTo(formGroup);

        // set all select using select2
        $('.select2').select2();

        // increment variable i
        i++;
    });

    // remove column button click event
    $(document).on('click', '.column-form .fa-times', function() {
        (this).closest('.column-form').remove();
    });
});

// customization table
$('#customTable').on('change', function () {
    if ($(this).val() === 'hide') {
        // set value checkbox = show
        $(this).val('show')

        const table = customizeTableHeader();

        // get table description
        $.ajax({
            url: '/packages/exercises/getCustomizeTable/' + $('#db_name').text() + '/' + $(this).data('table'),
            type: 'GET',
            dataType: 'json',
        }).then(data => {
            // add data to data-desc on submit modal
            $('#submitAdditional').attr('data-desc', JSON.stringify(data))
            // call function to generate table body customize
            customizeTableBody(data, table)
            $('#additions').val(JSON.stringify(data))
        });

        // show select dropdown
        $('#additional').removeClass('d-none')
    } else {
        $('#customizationTable').text('');
        $(this).val('hide')
        $('#additional').addClass('d-none')
        $('#additions').val('')

    }
})

const customizeTableHeader = () => {
    // create responsive cover
    const responsive = $('<div></div>').addClass('table-responsive').appendTo($('#customizationTable'));

    // create table element
    const table = $('<table></table>').addClass('table table-sm table-bordered').appendTo(responsive);
    const headers = ['Nama Tabel', 'Nama Kolom', 'Tipe Data', 'Size', 'Key', 'Nullability', 'Default', 'Extra']

    // create table header
    const thead = $('<thead></thead>').appendTo(table);
    const tr = $('<tr></tr>').appendTo(thead);
    headers.forEach(header => {
        $('<th></th>').text(header).appendTo(tr);
    });

    return table;
}

// generate table body customize
const customizeTableBody = (data, table) => {
    // create table body
    const tbody = $('<tbody></tbody>').appendTo(table);

    // count max row
    const numRows = Math.max(
        data.tableName.desc.length + data.tableName.additional.length,
        data.columnName.desc.length + data.columnName.additional.length,
        data.dataType.length,
        data.columnSize.desc.length + data.columnSize.additional.length,
        data.columnKey.length,
        data.columnNullability.length,
        data.columnDefault.desc.length + data.columnDefault.additional.length,
        data.columnExtra.length,
    );

    // insert data to table body
    for (let i = 0; i < numRows; i++) {
        // create row
        const tr = $('<tr></tr>').appendTo(tbody);

        // insert table name
        if (data.tableName.desc[i] !== undefined) {
            $('<td></td>').text(data.tableName.desc[i]).appendTo(tr);
        } else if (data.tableName.additional[i - data.tableName.desc.length] !== undefined) {
            const td = $('<td></td>').addClass('d-flex justify-content-between')
                .text(data.tableName.additional[i - data.tableName.desc.length]).appendTo(tr);
            const deleteButton = $('<a></a>').attr('href', '#').appendTo(td)
            const iconDelete = $('<a></a>').addClass('fas fa-trash text-danger').appendTo(deleteButton)
        } else {
            $('<td></td>').text('').appendTo(tr);
        }

        // insert column name
        if (data.columnName.desc[i] !== undefined) {
            $('<td></td>').text(data.columnName.desc[i]).appendTo(tr);
        } else if (data.columnName.additional[i - data.columnName.desc.length] !== undefined) {
            const td = $('<td></td>').addClass('d-flex justify-content-between')
                .text(data.columnName.additional[i - data.columnName.desc.length]).appendTo(tr);
            const deleteButton = $('<a></a>').attr('href', '#').appendTo(td)
            const iconDelete = $('<a></a>').addClass('fas fa-trash text-danger').appendTo(deleteButton)
        } else {
            $('<td></td>').text('').appendTo(tr);
        }

        // insert data type
        if (data.dataType[i] !== undefined) {
            $('<td></td>').text(data.dataType[i]).appendTo(tr);
        } else {
            $('<td></td>').text('').appendTo(tr);
        }

        // insert column size
        if (data.columnSize.desc[i] !== undefined) {
            $('<td></td>').text(data.columnSize.desc[i]).appendTo(tr);
        } else if (data.columnSize.additional[i - data.columnSize.desc.length] !== undefined) {
            const td = $('<td></td>').addClass('d-flex justify-content-between')
                .text(data.columnSize.additional[i - data.columnSize.desc.length]).appendTo(tr);
            const deleteButton = $('<a></a>').attr('href', '#').appendTo(td)
            const iconDelete = $('<a></a>').addClass('fas fa-trash text-danger').appendTo(deleteButton)
        } else {
            $('<td></td>').text('').appendTo(tr);
        }

        // insert column key
        if (data.columnKey[i] !== undefined) {
            $('<td></td>').text(data.columnKey[i]).appendTo(tr);
        } else {
            $('<td></td>').text('').appendTo(tr);
        }

        // insert column nullability
        if (data.columnNullability[i] !== undefined) {
            $('<td></td>').text(data.columnNullability[i]).appendTo(tr);
        } else {
            $('<td></td>').text('').appendTo(tr);
        }

        // insert column default
        if (data.columnDefault.desc[i] !== undefined) {
            $('<td></td>').text(data.columnDefault.desc[i]).appendTo(tr);
        } else if (data.columnDefault.additional[i - data.columnDefault.desc.length] !== undefined) {
            const td = $('<td></td>')
                .addClass('d-flex justify-content-between')
                .text(data.columnDefault.additional[i - data.columnDefault.desc.length])
                .appendTo(tr);
            const deleteButton = $('<a></a>')
                .attr('href', '#')
                .attr('data-type', 'columnDefault')
                .appendTo(td)
            const iconDelete = $('<a></a>').addClass('fas fa-trash text-danger').appendTo(deleteButton)
        } else {
            $('<td></td>').text('').appendTo(tr);
        }

        // insert column extra
        if (data.columnExtra[i] !== undefined) {
            $('<td></td>').text(data.columnExtra[i]).appendTo(tr);
        } else {
            $('<td></td>').text('').appendTo(tr);
        }
    }
}

// function modal add additional
$('#additional').on('change', function () {
    // show modal
    $('#add-addition').modal('show');
    // get type
    let type = $(this).val();
    // setup label for placeholder and select label
    let label;

    switch (type) {
        case 'tableName': label = 'Nama Tabel'; break;
        case 'columnName': label = 'Nama Kolom'; break;
        case 'columnSize': label = 'Size Kolom'; break;
        case 'columnDefault': label = 'Default Value Kolom'; break;
    }
    $('#content').attr('placeholder', label).attr('data-type', type);
    $('#contentLabel').text(label);
});

// submit additional
$('#submitAdditional').on('click', function () {
    // get data from attribute data-desc
    let data = $(this).data('desc')
    // get value and data-type from content
    let content = $('#content')
    let type;
    $('#additional option:selected').each(function() {
        type = $(this).val();
    });
    let value = content.val();
    // push value to data
    data[type]['additional'].push(value)
    // hide modal
    $('#add-addition').modal('hide');

    // reset content value
    content.val('');
    $('#additional')[0].selectedIndex = 0;

    // reset customize table
    $('#customizationTable').text('');

    // generate table customize
    const table = customizeTableHeader();
    customizeTableBody(data, table);
    $('#additions').val(JSON.stringify(data))
});

// reset select additional when modal dismiss
$('#add-addition').on('hidden.bs.modal', function () {
    $('#additional')[0].selectedIndex = 0;
})

// initiation stepper
const stepper = new Stepper(document.getElementById('stepper'), {
    linear: true,
    animation: true,
});
$('.step.active > .step-trigger > .wizard-step').addClass('wizard-step-active');

// next step
function nextStep() {
    if ($('#database_id').val() !== '') {
        let table = $('input[name=table][type=radio]:checked');
        let numberOfChecked = table.length;
        if (numberOfChecked !== 0) {
            $('.wizard-step-active').removeClass('wizard-step-active');
            stepper.next();
            let currentStep = $('.bs-stepper-header > .step.active > .step-trigger > .wizard-step');
            currentStep.addClass('wizard-step-active');

            const container = $('#accordion_selected');

            // reset container
            container.empty();

            // get data
            $.ajax({
                url: '/packages/exercises/getTableDesc/' + $('#db_name').text() + '/' + table.val(),
                type: 'GET',
                dataType: 'json',
            }).then(data => {
                generateAccordionSelectedTable(container, data);
                $('<input>', {type: 'hidden', id: 'selectedTable'}).val(table.val()).appendTo(container);
                $('#answer').val(`DESC ${table.val()}`);
                $('#customTable').attr('data-table', `${table.val()}`);
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
    $('#customizationTable').html();
    stepper.previous();
    let currentStep = $('.bs-stepper-header > .step.active > .step-trigger > .wizard-step');
    currentStep.addClass('wizard-step-active');
}

// submit form
function submitForm() {
    if ($('#question').val() !== '') {
        // init array for answer
        let answer = {
            table: $('#selectedTable').val(),
            columns: []
        };

        const columnForms = document.querySelectorAll('div.column-form');

        columnForms.forEach(function(form) {
            const columnNameInput = form.querySelector('input[name="columnName[]"]');
            const dataTypeInput = form.querySelector('select[name="dataType[]"]');
            const columnSizeInput = form.querySelector('input[name="columnSize[]"]');
            const columnKeyInput = form.querySelector('select[name="columnKey[]"]');
            const columnNullabilityInput = form.querySelector('select[name="columnNullability[]"]');
            const columnDefaultInput = form.querySelector('input[name="columnDefault[]"]');
            const columnExtraInput = form.querySelector('select[name="columnExtra[]"]');

            const columnNameValue = columnNameInput.value;
            const dataTypeValue = dataTypeInput.value;
            const columnSizeValue = columnSizeInput.value;
            const columnKeyValue = columnKeyInput.value;
            const columnNullabilityValue = columnNullabilityInput.value;
            const columnDefaultValue = columnDefaultInput.value;
            const columnExtraValue = columnExtraInput.value;

            answer.columns.push({
                name: columnNameValue,
                type: columnSizeValue !== '' ? `${dataTypeValue}(${columnSizeValue})` : dataTypeValue,
                nullability: columnNullabilityValue,
                key: columnKeyValue,
                default: columnDefaultValue,
                extra: columnExtraValue,
            })
        });

        // Set value answer input field
        $('#answer').val(JSON.stringify(answer));

        // submit form
        $('#create-exercise-form').submit();
    } else {
        $('#invalidQuestion').removeClass('d-none')
        $('.note-editor').addClass('invalid-summernote')
    }
}

