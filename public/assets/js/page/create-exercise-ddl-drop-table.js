// show table list by database
$('#database').on('change', function () {
    // get db_name
    let db_name = $(this).val();

    // set database_id
    let db_id = $(this).find('option:selected').data('id');
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
            $('#customization').removeClass('d-none')
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
        $('<input>', {
            type: 'checkbox',
            value: data.name,
            name: 'answer[]',
            id: data.name,
            class: 'myCheckbox',
        }).appendTo(header);

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
    const thead = $('<thead></thead>').appendTo(table);
    const tr = $('<tr></tr>').appendTo(thead);
    headers.forEach(header => {
        $('<th></th>').text(header).appendTo(tr);
    });

    // create table body
    const tbody = $('<tbody></tbody>').appendTo(table);
    tableData.columns.forEach(row => {
        const tr = $('<tr></tr>').appendTo(tbody);
        $('<td></td>').text(row.name).appendTo(tr);
        $('<td></td>').text(row.type).appendTo(tr);
        $('<td></td>').text(row.key).appendTo(tr);
        $('<td></td>').text(row.null).appendTo(tr);
        $('<td></td>').text(row.default).appendTo(tr);
        $('<td></td>').text(row.extra).appendTo(tr);
    });
};

// customization table
$('#customTable').on('change', function () {
    if ($(this).val() === 'hide') {
        // set value checkbox = show
        $(this).val('show')

        const table = customizeTableHeader();
        // get table description
        $.ajax({
            url: '/packages/exercises/getTableList/' + $('#database').val(),
            type: 'GET',
            dataType: 'json',
        }).then(data => {
            data = JSON.parse(data);
            let tables = {
                tableName: {
                    desc: [],
                    additional: [],
                },
                columnName: {
                    desc: [],
                    additional: [],
                },
                columnSize: {
                    desc: [],
                    additional: [],
                },
                columnDefault: {
                    desc: [],
                    additional: [],
                }
            };
            data.forEach((item) => {
                tables.tableName.desc.push(item.name);
            });

            // add data to data-desc on submit modal
            $('#submitAdditional').attr('data-desc', JSON.stringify(tables))
            // call function to generate table body customize
            customizeTableBody(tables, table)
            $('#additions').val(JSON.stringify(tables))
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

    // create table header
    const thead = $('<thead></thead>').appendTo(table);
    const tr = $('<tr></tr>').appendTo(thead);
    $('<th></th>').text('Nama Tabel').appendTo(tr);

    return table;
}

// generate table body customize
const customizeTableBody = (data, table) => {
    // create table body
    const tbody = $('<tbody></tbody>').appendTo(table);

    // count max row
    const numRows = Math.max(
        data.tableName.desc.length + data.tableName.additional.length
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

// submit form
function submitForm() {
    if ($('#database').val() !== null) {
        let table = $('input.myCheckbox[type=checkbox]:checked');
        let numberOfChecked = table.length;
        console.log(numberOfChecked)
        if (numberOfChecked !== 0) {
            $('#invalidTable').addClass('d-none')
            if ($('#question').val() !== '') {
                $('#create-exercise-form').submit();
            } else {
                $('#invalidQuestion').removeClass('d-none')
            }
        } else {
            $('#invalidTable').removeClass('d-none')
        }
    } else {
        $('#database + span').addClass('is-invalid')
        $('#invalidDatabase').removeClass('d-none')
    }

}

