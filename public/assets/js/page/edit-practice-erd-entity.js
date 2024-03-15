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
    btnAddMultivalue.addEventListener("click", function () {
        $('#input_wrappermultivalue').show();
    });
    $('#addattributeMultivalue').on('click', function () {
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
    // var json = JSON.stringify(data);
    // console.log(data);
    graph.fromJSON(JSON.parse(json));
    console.log(graph);
    jsonStore = graph;
}

function storeJson() {
    var jsonJoinJs = JSON.stringify(jsonStore.toJSON());
    var db_id = $('#db_id').val();
    var id = $('#exercise_id').val();
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
        formData.append('id', id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
        });
        $.ajax({
            url: '/packages/exercises/erd/entity/update',
            type: 'POST',
            data: formData,
            success: function (data) {
                showDoneAlert("Berhasil", data.url);
                // window.location.href = data.url;
            },
            error: function (data) {
                showErrorModal('Terjadi Error');
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }
    //    console.log(jsonJoinJs);
}
