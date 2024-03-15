

// show table list by database
$('#database').on('change', function () {
    document.body.className = "sidebar-mini";
    // get db_name & db_id
    var db_name = $(this).val();
    var db_id = $(this).find('option:selected').data('id');
    // set db_name & db_id for next step
    $('#db_name').html(db_name);
    $('#database_id').val(db_name);
    $('.table-responsive').show();
    $('#list_entity_table').empty();

    // get table list from selected database
    $.ajax({
        url: '/packages/exercises/erd/entity/get/?db_name=' + db_name + '&action=table_list',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data)
            $.each(data, function (i, item) {
                var fields = "<table>";
                var types = "<table>";
                var tr = '<tr rowspan="' + item.type.length + '">';
                $.each(item.type, function (j, type) {
                    fields += '<tr><td>' + type.Field + '</td></tr>';
                    types += '<tr><td>' + type.Type + '</td></tr>';
                });
                fields += "</table>";
                types += "</table>";
                $('#list_entity_table').append(tr + '<td>' + item.value + '</td><td>Kolom entity</td><td>' + fields + '</td><td>' + types + '</td></tr>');
            });
        }
    });
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

var erd = joint.shapes.erd;
// show selected table list
function generateJoinJsEntity(data) {

    let amountdata = 2;
    let heightFrame = amountdata * 300;
    var namespace = joint.shapes;
    var graph = new joint.dia.Graph({}, { cellNamespace: namespace });
    var paper = new joint.dia.Paper({
        el: document.getElementById('joinEntityGraph'),
        model: graph,
        width: '100%',
        height: heightFrame,
        drawGrid: true,
        gridSize: 1,
        linkPinning: false,
        background: {
            color: 'rgba(149, 149, 149, 0.8)'
        },
        cellViewNamespace: namespace
    });

    let btnAddCustomeEntity = document.getElementById("btnAddCustomeEntity");
    btnAddCustomeEntity.addEventListener("click", function () {
        $('#input_wrapper').show();
    });
    $('#add').on('click', function () {
        let entity = new joint.shapes.standard.Rectangle();
        entity.position(100, 100);
        entity.resize(100, 40);
        entity.attr({
            body: {
                magnet: true,
                fill: "red",
                strokeWidth: 4,
                strokeDasharray: 2,
                stroke: 'black',
            },
            label: {
                text: $('#name').val(),
                fill: 'White',
                fontSize: 14,
                letterSpacing: 0,
                style: {
                    textShadow: "1px 0 1px #333333"
                },
            },
        });
        graph.addCell(entity);
    });

    let btnAddCustomeAttribute = document.getElementById("btnAddCustomeAttribute");
    btnAddCustomeAttribute.addEventListener("click", function () {
        $('#input_wrappercustome').show();
    });
    $('#addattribute').on('click', function () {
        let attribute = new joint.shapes.standard.Ellipse();
        attribute.position(100, 100);
        attribute.resize(100, 40);
        attribute.attr({
            body: {
                fill: 'orange',
            },
            label: {
                text: $('#nameattribute').val(),
                fill: 'White',
                magnet: true,
                letterSpacing: 0,
                style: {
                    textShadow: "1px 0 1px #333333",
                    textDecoration: "underline"
                },
            }
        });
        graph.addCell(attribute);
    });

    let btnAddComposite = document.getElementById("btnAddComposite");
    btnAddComposite.addEventListener("click", function () {
        $('#input_wrappercomposite').show();
    });
    $('#addattributeComposite').on('click', function () {
        let attribute = new joint.shapes.standard.Ellipse();
        attribute.position(100, 100);
        attribute.resize(100, 40);
        attribute.attr({
            body: {
                fill: 'green',
                magnet: true
            },
            label: {
                text: $('#nameattributeComposite').val(),
                fill: 'white',
                letterSpacing: 0,
                style: {
                    textShadow: "1px 0 1px #333333"
                },
            }
        });
        graph.addCell(attribute);
    });

    let btnAddDerived = document.getElementById("btnAddDerived");
    btnAddDerived.addEventListener("click", function () {
        $('#input_wrapperderived').show();
    });
    $('#addattributeDerived').on('click', function () {
        let attribute = new joint.shapes.standard.Ellipse();
        attribute.position(100, 100);
        attribute.resize(100, 40);
        attribute.attr({
            body: {
                fill: '#00ff9f',
                magnet: true,
                strokeWidth: 3,
                strokeDasharray: 3,
                stroke: 'black',
            },
            label: {
                text: $('#nameattributeDerived').val(),
                fill: 'black',
                letterSpacing: 0,
                style: {
                    textShadow: "1px 0 1px #333333"
                },
            }
        });
        graph.addCell(attribute);
    });

    let btnAddMultivalue = document.getElementById("btnAddMultivalue");
    btnAddMultivalue.addEventListener("click", function() {
        $('#input_wrappermultivalue').show();
    });
    $('#addattributeMultivalue').on('click', function() {
        let attribute = new joint.shapes.standard.Ellipse();
        attribute.position(100, 300);
        attribute.resize(100, 40);
        attribute.attr({
            body: {
                fill: '#95E1D3',
                magnet: true,
                strokeWidth: 6,
            },
            label: {
                text: $('#nameattributeMultivalue').val(),
                fill: 'black',
                letterSpacing: 0,
                style: {
                    textShadow: "1px 0 1px #333333"
                },
            }
        });
        graph.addCell(attribute);
    });

    // Register events
    paper.on('link:mouseenter', (linkView) => {
        showLinkTools(linkView);
    });

    paper.on('link:mouseleave', (linkView) => {
        linkView.removeTools();
    });

    paper.on("element:pointerclick", function (elementView, evt, x, y) {
        var boundaryTool = new joint.elementTools.Boundary();
        var removeButton = new joint.elementTools.Remove();

        var toolsView = new joint.dia.ToolsView({
            tools: [boundaryTool, removeButton],
        });

        elementView.addTools(toolsView);
    });

    function showLinkTools(linkView) {
        var tools = new joint.dia.ToolsView({
            tools: [
                new joint.linkTools.Remove({
                    distance: '50%',
                    markup: [{
                        tagName: 'circle',
                        selector: 'button',
                        attributes: {
                            'r': 7,
                            'fill': '#f6f6f6',
                            'stroke': '#ff5148',
                            'stroke-width': 2,
                            'cursor': 'pointer'
                        }
                    },
                    {
                        tagName: 'path',
                        selector: 'icon',
                        attributes: {
                            'd': 'M -3 -3 3 3 M -3 3 3 -3',
                            'fill': 'none',
                            'stroke': '#ff5148',
                            'stroke-width': 2,
                            'pointer-events': 'none'
                        }
                    }
                    ]
                })
            ]
        });
        linkView.addTools(tools);
    }
    var json = JSON.stringify(data);
    console.log(data);
    graph.fromJSON(JSON.parse(json));
    console.log(graph);
    jsonStore = graph;
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
function nextStep() {
    if ($('#database_id').val() != '') {
        let label = 0;
        $('.label_fix').each(function () {
            if ($(this).val() == '') {
                label++;
            }
        });
        if (label > 0) {
            $('#error_erd').empty();
            $('#error_erd').show();
            $('#error_erd').append('Harap isi semua label diatas');
            console.log(label);
        } else {
            var mtom = $('#relation_check_m_m').val();
            $('#invalidTable').removeClass('d-none')
            $('.accordion-header').addClass('invalid-accordion')
            $('.wizard-step-active').removeClass('wizard-step-active');
            stepper.next();
            let currentStep = $('.bs-stepper-header > .step.active > .step-trigger > .wizard-step');
            currentStep.addClass('wizard-step-active');
            const container = $('#accordion_selected');
            container.empty();
            var CSRF_TOKEN = $('#_token_').val();
            var formData = new FormData();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            $.ajax({
                url: '/packages/exercises/erd/entity/get/?db_name=' + $('#database_id').val() + '&action=table_list',
                type: 'POST',
                data: formData,
                success: function (data) {
                    generateJoinJsEntity(data);
                    console.log(data)
                },
                error: function (data) {
                    console.log(data);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
    } else {
        $('#database + span').addClass('is-invalid')
        $('#invalidDatabase').removeClass('d-none')
    }
};

// previous step
function previousStep() {
    $('.wizard-step-active').removeClass('wizard-step-active');
    stepper.previous();
    let currentStep = $('.bs-stepper-header > .step.active > .step-trigger > .wizard-step');
    currentStep.addClass('wizard-step-active');
}

// submit form
function submitForm() {
    if ($('#question').val() != '' && $('#answer').val() != '') {
        $('#create-practice-form').submit();
    } else {
        $('#invalidQuestion').removeClass('d-none')
        $('#invalidAnswer').removeClass('d-none')
        $('.note-editor').addClass('invalid-summernote')
        $('#answer').addClass('is-invalid')
    }
}

function storeJson() {
    var jsonJoinJs = JSON.stringify(jsonStore.toJSON());
    var db_id = $('#database').find('option:selected').data('id');
    var question = $('#question').val();
    var package_id = $('#package_id').val();
    if (question == '') {
        alert('Mohon isi pertanyaan')
    } else {
        var CSRF_TOKEN = $('#_token_').val();
        var formData = new FormData();
        formData.append('database_id', db_id);
        formData.append('package_id', package_id);
        formData.append('question', question);
        formData.append('answer', jsonJoinJs);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
        });
        $.ajax({
            url: '/packages/exercises/erd/entity/store',
            type: 'POST',
            data: formData,
            success: function (data) {
                //console.log(data);
                window.location.href = data.url;
            },
            error: function (data) {
                console.log(data);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }
    //    console.log(jsonJoinJs);
}