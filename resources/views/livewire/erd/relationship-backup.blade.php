 @push('customStyle')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/joinjs/css/joint.css') }}">
@endpush
        <h2 class="section-title">{!! $exercise->package->name !!}</h2>

        <div clasexercises="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Pertanyaan</h4>
                    </div>
                    <div class="card-body">
                        <p style="font-size: 1rem; line-height: 1.5">{!! $exercise->question !!}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Lembar Kerja</h4>
                            </div>
                            <div class="card-body" >
                                <div class="col-lg-12 col-md-9" id="joinJsGraph" >
                    
                                </div>
                                <div align="right" style="padding-top: 2%;">
                                    <div class="d-flex justify-content-between">
                                        @if($questionNumber == 1)
                                        <button class="btn btn-flat" disabled>
                                        <i class="fas fa-chevron-left"></i> Sebelumnya</button>
                                        @else
                                        <button class="btn btn-flat" disabled
                                                onclick="previousStep('{{$schedule->id}}','{{$questionNumber}}','{{$exercise->id}}')">
                                        <i class="fas fa-chevron-left"></i> Sebelumnya</button>
                                        @endif
                                        <div class="row mt-1">
                                            <div class="col-md-12 text-right">
                                                @if($done == 1)
                                                    <button class="btn btn-flat" disabled>Reset</button>
                                                @else
                                                    <button class="btn btn-flat" onclick="resetAnswer()">Reset</button>
                                                @endif
                                                @if($questionNumber == $totalexercise)
                                                <a class="btn btn-primary"
                                                    onclick="submitAnswer('{{$schedule->id}}','{{$questionNumber}}','{{$exercise->id}}')">
                                                    Submit
                                                </a>
                                                @else
                                                <a class="btn btn-primary"
                                                    onclick="nextStep('{{$schedule->id}}','{{$questionNumber}}','{{$exercise->id}}')">
                                                    Lanjut
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@push('customScript')
<script src="{{ asset('assets/joinjs/js/lodash.js') }}"></script>
<script src="{{ asset('assets/joinjs/js/backbone.js') }}"></script>
<script src="{{ asset('assets/joinjs/js/joint.js') }}"></script>
<script type="text/javascript">
          var json = '<?php echo $fixJson?>';
          var jsonDef = '<?php echo $fixJsonDef?>';
</script>
<script src="{{ asset('assets/js/page/show-practice-erd-relationship.js') }}"></script>
<script type="text/javascript">
    function balikYa(schedule,question)
    {
        var url = '<?php echo url('exercise')?>'+'/'+schedule+'?question='+question;
        window.location.href = url;
    }

    function showIfBypass() 
    {
        showErrorToast('Maaf kamu gabisa lanjut dengan cara bypass url ya, karena ada jawban yg masih belum benar silahkan !');
        localStorage.removeItem('bypass');
    }
    window.onload = function(){
        document.body.className = "sidebar-mini";
        
        var bypass = '{{$bypass}}';
        if(bypass == '1')
        {
            var schedule = '{{$schedule->id}}';
            var question = '{{$questionNumberMust}}';
            localStorage.setItem("bypass", "1");
            balikYa(schedule,question);
        }else{
            var msgBypass = localStorage.getItem('bypass');
            if(msgBypass != null)
            {
                showIfBypass();
            }
            setTimeout(generateEditenJoinJs, 1000);
        } 
    };

    function nextStep(schedule,question,exercise) 
    {
        var jsonJoinJs = JSON.stringify(jsonStore.toJSON());
        var CSRF_TOKEN = '{{ csrf_token() }}';
        var formData = new FormData();
        formData.append('exercise_id',exercise);
        formData.append('schedule_id',schedule);
        formData.append('question',question);
        formData.append('answer',jsonJoinJs);
        formData.append('step','step');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
        });
        $.ajax({
            url: "{{url('student/exercise/relationship/next_step')}}",
            type: 'POST',
            data: formData,
            success: function (data) {
               if(data.data == 'correct')
               {
                    showSuccessToast(data.message);
                    window.location.href = data.url;
               }else{
                   showErrorToast(data.message); 
               }
               
            },
            error: function(data){
                console.log(data);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    function previousStep(schedule,question,exercise) 
    {
        question = question - 1;
        var url = '<?php echo url('exercise')?>'+'/'+schedule+'?question='+question;
        window.location.href = url;
    }

    function resetAnswer() 
    {
        $('#joinJsGraph').empty();
        generateEditenJoinJsDef();
    }

    function submitAnswer(schedule,question,exercise) 
    {
        var jsonJoinJs = JSON.stringify(jsonStore.toJSON());
        var CSRF_TOKEN = '{{ csrf_token() }}';
        var formData = new FormData();
        formData.append('exercise_id',exercise);
        formData.append('schedule_id',schedule);
        formData.append('question',question);
        formData.append('answer',jsonJoinJs);
        formData.append('step','last');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
        });
        $.ajax({
            url: "{{url('student/exercise/relationship/next_step')}}",
            type: 'POST',
            data: formData,
            success: function (data) {
               if(data.data == 'correct')
               {
                    showDoneAlert(data.message,data.url);
               }else{
                   showErrorToast(data.message); 
               }
               
            },
            error: function(data){
                console.log(data);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

    function showErrorToast(text) {
         toastr.error(text, null, {
            closeButton: true,
            timeOut: 5000,
            positionClass: 'toast-bottom-right',
        });
    }

    function showSuccessToast(text) {
        toastr.success(text, null, {
            closeButton: true,
            timeOut: 5000,
            positionClass: 'toast-bottom-right',
        });
    }
    function showDoneAlert(text,url) {
        Swal.fire({
            title: 'Latihan Selesai!',
            text: text,
            icon: 'success',
            confirmButtonText: 'Kembali ke Halaman Utama'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>
@endpush

