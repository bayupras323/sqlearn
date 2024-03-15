var arrJsonSave;
var jsonStore;
var erd = joint.shapes.standard.Rectangle;
var graph;
function generateEditenJoinJsEntity(data) {
    let amountdata = 2;
    let heightFrame = amountdata * 300;
    var namespace = joint.shapes;
    graph = new joint.dia.Graph({}, { cellNamespace: namespace });
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
    graph.fromJSON(JSON.parse(json));
    jsonStore = graph;
    console.log(jsonStore);
}

function resetGraph(){
    graph.resetCells();
    jsonStore = graph;
    console.log(jsonStore);
}

function generateEditenJoinJsEntityDef(data) {
    let amountdata = 2;
    let heightFrame = amountdata * 300;
    var namespace = joint.shapes;
    graph = new joint.dia.Graph({}, { cellNamespace: namespace });
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
    graph.fromJSON(JSON.parse(jsonDef));
    jsonStore = graph;
    console.log(jsonStore);
}
