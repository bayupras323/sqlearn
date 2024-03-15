var arrJsonSave;
var jsonStore;
function generateEditenJoinJs(data) {
    //console.log(json);
        var namespace = joint.shapes;
        var graph = new joint.dia.Graph({}, { cellNamespace: namespace });
        var paper = new joint.dia.Paper({
            el: document.getElementById('joinJsGraph'),
            model: graph,
            width: '100%',
            height: 600,
            gridSize: 1,
            background: {
                color: 'rgba(255, 165, 0, 0.3)'
            },
            cellViewNamespace: namespace
        });
    graph.fromJSON(JSON.parse(json));
    jsonStore = graph;
}

function generateEditenJoinJsDef(data) {
    //console.log(json);
        var namespace = joint.shapes;
        var graph = new joint.dia.Graph({}, { cellNamespace: namespace });
        var paper = new joint.dia.Paper({
            el: document.getElementById('joinJsGraph'),
            model: graph,
            width: '100%',
            height: 600,
            gridSize: 1,
            background: {
                color: 'rgba(255, 165, 0, 0.3)'
            },
            cellViewNamespace: namespace
        });
    graph.fromJSON(JSON.parse(jsonDef));
    jsonStore = graph;
}