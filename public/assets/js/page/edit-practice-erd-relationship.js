

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


var erd = joint.shapes.erd;
var jsonStore;
// show selected table list
function generateJoinJsRelation(data) {
    let amountdata = 2;
    let heightFrame = amountdata * 300;
    var namespace = joint.shapes;
    var graph = new joint.dia.Graph({}, { cellNamespace: namespace });
    var paper = new joint.dia.Paper({
        el: document.getElementById('joinJsGraph'),
        model: graph,
        width: '100%',
        height: heightFrame,
        drawGrid: true,
        gridSize: 1,
        linkPinning: false,
        background: {
            color: 'rgba(255, 165, 0, 0.3)'
        },
        cellViewNamespace: namespace
    });

    let btnAddCustomeEntity = document.getElementById("btnAddCustomeEntity");
    btnAddCustomeEntity.addEventListener("click", function () {
        $('#input_wrapper').show();
    });

    let btnAddCustomeEntityDel = document.getElementById("closeCustome");
    btnAddCustomeEntityDel.addEventListener("click", function () {
        $('#input_wrapper').hide();
    });

    $('#add').on('click', function () {
        if($('#name').val() == '')
        {
            alert('Harap isi label entity');
        }else{
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
            $('#name').val('');
        }
    });

    let btnAddCustomeAttribute = document.getElementById("btnAddCustomeAttribute");
    btnAddCustomeAttribute.addEventListener("click", function () {
        $('#input_wrappercustome').show();
    });
    let btnAddCustomeAttributeDel = document.getElementById("closeCustomeAttr");
    btnAddCustomeAttributeDel.addEventListener("click", function () {
        $('#input_wrappercustome').hide();
    });

   
        $('#addattribute').on('click', function () {
        if($('#nameattribute').val() == '')
        {
            alert('Harap isi label attribute key primary');
        }else{
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
            $('#nameattribute').val('');
        }
    });


    let btnAddComposite = document.getElementById("btnAddComposite");
    btnAddComposite.addEventListener("click", function () {
        $('#input_wrappercomposite').show();
    });

    let btnAddCompositeDel = document.getElementById("closeComposite");
    btnAddCompositeDel.addEventListener("click", function () {
        $('#input_wrappercomposite').hide();
    });

        $('#addattributeComposite').on('click', function () {
        if($('#nameattributeComposite').val() == '')
        {
            alert('Harap isi label attribute composit');
        }else{
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
                $('#nameattributeComposite').val('');
            }
        });

    let btnAddDerived = document.getElementById("btnAddDerived");
    btnAddDerived.addEventListener("click", function () {
        $('#input_wrapperderived').show();
    });

    let btnAddDerivedDel = document.getElementById("closeDerived");
    btnAddDerivedDel.addEventListener("click", function () {
        $('#input_wrapperderived').hide();
    });

   
        $('#addattributeDerived').on('click', function () {
        if($('#nameattributeDerived').val() == '')
        {
            alert('Harap isi label attribute derivatif');
        }else{
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
            $('#nameattributeDerived').val('');
            }
        });
    

    //relationshae
    let btnAddRelation = document.getElementById("btnAddRelation");
    btnAddRelation.addEventListener("click", function () {
        $('#input_wrapper_relation').show();
    });

    let btnAddRelationDel = document.getElementById("closeRelation");
    btnAddRelationDel.addEventListener("click", function () {
        $('#input_wrapper_relation').hide();
    });

    $('#addRelationShape').on('click', function () {
        var portsIn = {
            position: {
                name: 'left'
            },
            attrs: {
                portBody: {
                    magnet: true,
                    r: 10,
                    fill: '#023047',
                    stroke: '#023047'
                }
            },
            label: {
                position: {
                    name: 'left',
                    args: { y: 6 } 
                },
                markup: [{
                    tagName: 'text',
                    selector: 'label',
                    className: 'label-text'
                }]
            },
            markup: [{
                tagName: 'circle',
                selector: 'portBody'
            }]
        };

        var portsOut = {
            position: {
                name: 'right'
            },
            attrs: {
                portBody: {
                    magnet: true,
                    r: 10,
                    fill: '#E6A502',
                    stroke:'#023047'
                }
            },
            label: {
                position: {
                    name: 'right',
                    args: { y: 6 }
                },
                markup: [{
                    tagName: 'text',
                    selector: 'label',
                    className: 'label-text'
                }]
            },
            markup: [{
                tagName: 'circle',
                selector: 'portBody'
            }]
        };
        if($('#nameOfRelation').val() == '')
        {
            alert('Harap isi label relasi');
        }else{
            var attribute = new erd.Relationship({
                position: { x: 275, y: 50 },
                attrs: {
                text: {
                  fill: "black",
                  text: $('#nameOfRelation').val(),
                  letterSpacing: 0,
                 // style: { textShadow: "1px 0 1px #333333" },
                },
                ".outer": {
                 fill: '#E6A502',
                  stroke: "none",
                  filter: {
                    name: "dropShadow",
                    args: { dx: 0, dy: 2, blur: 1, color: "#333333" },
                  },
                },
              },
                ports: {
                    groups: {
                        'in': portsIn,
                        'out': portsOut
                    }
                },
            });
        }

        var relationType = $('#relationType').val();
        if(relationType == null)
        {
            alert('Harap pilih relasi');
        }else{
            var relIn;
            var relOut;
            if(relationType == 'one_to_one')
            {
                relIn = 'One';
                relOut = 'One';
            }
            if(relationType == 'one_to_many')
            {
                relIn = 'One';
                relOut = 'Many';
            }
            if(relationType == 'many_to_many')
            {
                relIn = 'Many';
                relOut = 'Many';
            }
            attribute.addPorts(
                [{ 
                    group: 'in',
                    attrs: { label: { text: relIn,fill:'black' }}
                },
                { 
                    group: 'out',
                    attrs: { label: { text: relOut,fill:'black' }}
                }
            ]);
            attribute.resize(100, 100);
            graph.addCell(attribute);
            $('#nameOfRelation').val('');
        }
        //console.log(relationType);
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
    graph.fromJSON(JSON.parse(data));
    jsonStore = graph;
}

$(document).ready(function(){
  generateJoinJsRelation(gas);
});

// initiation stepper
var stepper = new Stepper(document.getElementById('stepper'), {
    linear: true,
    animation: true,
});

// $('.bs-stepper-header > .step.active > .step-trigger > .wizard-step').addClass('wizard-step-active');



function storeJson() {
    var jsonJoinJs = JSON.stringify(jsonStore.toJSON());
   // var db_id = 0;
    var question = $('#question').val();
    var package_id = $('#package_id').val();
    if (question == '') {
        alert('Mohon isi pertanyaan')
    } else {
        var CSRF_TOKEN = $('#_token_').val();
        var formData = new FormData();
        formData.append('exercise_id', exercise_id);
        formData.append('package_id', package_id);
        formData.append('question', question);
        formData.append('answer', jsonJoinJs);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
        });
        $.ajax({
            url: '/packages/exercises/erd/relationship/update',
            type: 'POST',
            data: formData,
            success: function (data) {
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