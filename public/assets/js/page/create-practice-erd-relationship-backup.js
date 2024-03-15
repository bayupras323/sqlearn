// show table list by database
$('#database').on('change', function () {
    document.body.className = "sidebar-mini";
    // get db_name & db_id
    var db_name = $(this).val();
    var db_id = $(this).find('option:selected').data('id');
    // set db_name & db_id for next step
    $('#db_name').html(db_name);
    $('#database_id').val(db_name);

    // remove error message
    $('#database + span').removeClass('is-invalid')
    $('#invalidDatabase').addClass('d-none')

    // get table list from selected database
    if (db_name) {
        $.ajax({
            url: '/packages/exercises/erd/relationship/get/?db_name='+db_name+'&action=table_list',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#choose_db_first').hide();
                $('#error_erd').hide();
                $('#error_erd').empty();
                $('#list_relasi_tabel').empty();
                $('#show_relasi_table').hide();
                document.getElementById("loader_erd").style.display = '';
                document.getElementById("loader_erd_text").style.display = '';
                if(data.status)
                {
                    $('#list_relasi_tabel').empty();
                    $('#list_relasi_tabel').append(data.data);
                    $('#show_relasi_table').show();
                    document.getElementById("loader_erd").style.display = 'none';
                    document.getElementById("loader_erd_text").style.display = 'none';
                    $('#relation_check_m_m').val(data.mtom);
                }else{
                    $('#error_erd').show();
                    $('#error_erd').append(data.data);
                    document.getElementById("loader_erd").style.display = 'none';
                    document.getElementById("loader_erd_text").style.display = 'none';
                    $('#relation_check_m_m').val('');
                }
            }
        });
    } else {
        $('#choose_db_first').show();
        $('#list_relasi_tabel').empty();
        $('#show_relasi_table').hide();
        document.getElementById("loader_erd").style.display = 'none';
        document.getElementById("loader_erd_text").style.display = 'none';
        $('#relation_check_m_m').val('');
    }
});

//append to many_to_many
var satu_satu = new Array();
var oyi;
function fixMtom(val) 
{   
  if($('#mtom_'+val).is(":checked"))
  {
     oyi = $('#mtom_'+val).attr("data-table");
     satu_satu.push(oyi);
     $('#relation_check_m_m').val(satu_satu);
   }else{
    oyi = $('#mtom_'+val).attr("data-table");
    for (var key in satu_satu) {
      if (satu_satu[key] == oyi) {
        satu_satu.splice(key, 1);
      }
    }
    $('#relation_check_m_m').val(satu_satu);
   }

   //console.log($('#relation_check_m_m').val());
}


var arrJsonSave;
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
            perpendicularLinks: true,
            gridSize: 1,
            background: {
                color: 'rgba(255, 165, 0, 0.3)'
            },
            cellViewNamespace: namespace
        });
    console.log(JSON.stringify(data.joinjs.cells));
    // var json = JSON.stringify(data.joinjs);
    var json = JSON.stringify(data.joinjs);
    graph.fromJSON(JSON.parse(json));
    jsonStore = graph;
    //arrJsonSave = data.dataArr;
}

function storeJson(){
    var jsonJoinJs = JSON.stringify(jsonStore.toJSON());
    var db_id = $('#database').find('option:selected').data('id');
    var question = $('#question').val();
    var package_id = $('#package_id').val();
    if(question == '')
    {
        alert('Mohon isi pertanyaan')
    }else{
        var CSRF_TOKEN = $('#_token_').val();
        var formData = new FormData();
        formData.append('database_id',db_id);
        formData.append('package_id',package_id);
        formData.append('question',question);
        formData.append('answer',jsonJoinJs);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
        });
        $.ajax({
            url: '/packages/exercises/erd/relationship/store',
            type: 'POST',
            data: formData,
            success: function (data) {
               //console.log(data);
               window.location.href = data.url;
            },
            error: function(data){
                console.log(data);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }
   // console.log(jsonJoinJs);
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
            if($(this).val() == '')
            {
                label++;
            }
        });
        if(label > 0)
        {
            $('#error_erd').empty();
            $('#error_erd').show();
            $('#error_erd').append('Harap isi semua label diatas');
            console.log(label);
        }else{
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
            $('.cardinality_fix').each(function () {
                formData.append($(this).attr("data-table"), $(this).find(":selected").val());
            });
            $('.label_fix').each(function () {
                formData.append($(this).attr("data-table"), $(this).val());
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            $.ajax({
                url: '/packages/exercises/erd/relationship/get/?db_name='+$('#database_id').val()+'&action=table_arr&data='+mtom,
                type: 'POST',
                data: formData,
                success: function (data) {
                   generateJoinJsRelation(data);
                   //console.log(data.data);
                },
                error: function(data){
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
}

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