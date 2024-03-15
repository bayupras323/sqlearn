@push('customStyle')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/joinjs/css/joint.css') }}">
@endpush
<h2 class="section-title">{!! $exercise->package->name !!}</h2>
<div class="row">
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
                    <div class="card-body row">
                        <div class="col-lg-10 col-md-9" id="joinEntityGraph">

                        </div>
                        <div class="col-lg-2">
                            <button class="btn btn-primary" id="btnAddEntity">
                                Add Entity
                            </button>
                            <div id="input_wrapper" style="display: none;">
                                <input type="text" id="name" />
                                <button class="btn btn-success" id="add">
                                    Submit
                                </button>
                            </div>
                            <button class="btn btn-primary" id="btnAddCustomEntity">
                                Add Custom Entity
                            </button>
                            <div id="input_wrapper2" style="display: none;">
                                <input type="text" id="nameCustomEntity" />
                                <button class="btn btn-primary" id="addCustomEntity">
                                    Submit
                                </button>
                            </div>
                            <button class="btn btn-primary" id="btnAddAttribute">
                                Add Attribute
                            </button>
                            <div id="input_wrapper1" style="display: none;">
                                <input type="text" id="nameAttribute" />
                                <button class="btn btn-success" id="addAttribute">
                                    Submit
                                </button>
                            </div>
                            <button class="btn btn-primary" id="btnAddCustomAtribut">
                                Add Attribute Key
                            </button>
                            <div id="input_wrapper3" style="display: none;">
                                <input type="text" id="nameCustomAtribut" />
                                <button class="btn btn-success" id="addCustomAtribut">
                                    Submit
                                </button>
                            </div>
                            <button class="btn btn-primary" id="btnAddComposite">
                                Add Composite
                            </button>
                            <div id="input_wrappercomposite" style="display: none;">
                                <input type="text" id="nameattributeComposite" />
                                <button class="btn btn-primary" id="addattributeComposite">
                                    Submit
                                </button>
                            </div>
                            <button class="btn btn-primary mt-2" id="btnAddDerived">
                                Add Derived Attribute
                            </button>
                            <div id="input_wrapperderived" style="display: none;">
                                <input id="nameattributeDerived" />
                                <button type="button" class="btn btn-primary" id="addattributeDerived">
                                    Submit
                                </button>
                            </div>
                            <button class="btn btn-primary" id="btnAddMultivalue">
                                Add Multivalue
                            </button>
                            <div id="input_wrappermultivalue" style="display: none;">
                                <input type="text" id="nameattributeMultivalue" />
                                <button class="btn btn-primary" id="addattributeMultivalue">
                                    Submit
                                </button>
                            </div>
                        </div>
                        {{--  <div align="right" style="padding-top: 2%;">
                            <div class="d-flex justify-content-between">
                                @if ($questionNumber != 1)
                                    <a class="btn btn-flat"
                                        href="{{ url()->current() . '?question=' . $questionNumber - 1 }}">
                                        <i class="fas fa-chevron-left"></i> Sebelumnya</a>
                                @endif  --}}
                        <div class="row mt-1">
                            <div class="col-md-12 text-right">
                                @if ($done == 1)
                                    <button class="btn btn-flat" >Reset</button>
                                @else
                                    <button class="btn btn-flat" onclick="resetAnswer()">Reset</button>
                                @endif
                                <a class="btn btn-primary" id="btnSubmit">
                                    {{ $questionNumber == $totalexercise ? 'Submit' : 'Lanjut' }}
                                </a>
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
        var json = '{!! $fixJson !!}';
    </script>
    <script src="{{ asset('assets/js/page/show-practice-erd-entity.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            if("{{$bypass}}" == 1){
                window.location.href = "{{route('dashboard.user')}}";
            }
            $('body').addClass('sidebar-mini');
            generateEditenJoinJsEntity();
        });

        $('#btnSubmit').on('click', function(e) {
            e.preventDefault();
            var jsonJoinJs = JSON.stringify(jsonStore.toJSON());
            console.log(jsonStore.toJSON());
            var CSRF_TOKEN = "{{ csrf_token() }}";
            var formData = new FormData();
            formData.append('exercise_id', "{{ $exercise->id }}");
            formData.append('schedule_id', "{{ $schedule->id }}");
            formData.append('question', "{{ $questionNumber }}");
            formData.append('answer', jsonJoinJs);
            console.log(jsonJoinJs);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            $.ajax({
                url: "/student/exercise/entity/next_step",
                type: 'POST',
                data: formData,
                success: function(data) {
                    $('#btnNext').attr('disabled', true);
                    if (data.status == 'incorrect') {
                        //showErrorToast("Jawaban Salah!");
                        if(data.message){
                            showErrorModal(data.message);
                        } else {
                            showErrorModal("Jawaban Anda Masih Salah, Coba Periksa Lagi");
                        }
                    } else if (data.status == 'correct') {
                        if ("{{ $questionNumber != $totalexercise }}") {
                            showSuccessToast("Jawaban Tersimpan!");
                            window.location.replace(data.url);
                        } else {
                            showDoneAlert("Jawaban tersimpan! Kembali ke halaman utama", data.url);
                        }
                    }
                },
                error: function(data) {
                    showErrorToast(data.responseJSON.message);
                },
                cache: false,
                contentType: false,
                processData: false
            });
            $('#btnNext').removeAttr('disabled');
        })

        function resetAnswer() {
            resetGraph();
        }

        function showErrorToast(text) {
            toastr.error(text, null, {
                closeButton: true,
                timeOut: 5000,
                positionClass: 'toast-bottom-right',
            });
        }

        function showErrorModal(text) {
            Swal.fire({
                title: 'Mohon Maaf',
                text: text,
                icon: 'error',
            });
        }

        function showSuccessToast(text) {
            toastr.success(text, null, {
                closeButton: true,
                timeOut: 5000,
                positionClass: 'toast-bottom-right',
            });
        }

        function showDoneAlert(text, url) {
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

        // Add Entity
        let btnAddEntitiy = document.getElementById("btnAddEntity");
        btnAddEntitiy.addEventListener("click", function() {
            $('#input_wrapper').show();
        });
        $('#add').on('click', function() {
            let entity = new joint.shapes.standard.Rectangle();
            entity.position(100, 100);
            entity.resize(100, 40);
            entity.attr({
                body: {
                    magnet: true,
                    fill: "red",
                },
                label: {
                    text: $('#name').val(),
                    fill: 'White',
                    letterSpacing: 0,
                    style: {
                        textShadow: "1px 0 1px #333333"
                    },
                }
            });
            graph.addCell(entity);
        });

        //Custome Entity
        let btnAddCustomEntity = document.getElementById("btnAddCustomEntity");
        btnAddCustomEntity.addEventListener("click", function() {
            $('#input_wrapper2').show();
        });
        $('#addCustomEntity').on('click', function() {
            let entityCustome = new joint.shapes.standard.Rectangle();
            entityCustome.position(100, 100);
            entityCustome.resize(100, 40);
            entityCustome.attr({
                body: {
                    magnet: true,
                    fill: "#F38181",
                    strokeWidth: 4,
                    strokeDasharray: 2,
                    stroke: 'black',
                },
                label: {
                    text: $('#nameCustomEntity').val(),
                    fill: 'White',
                    fontSize: 14,
                    letterSpacing: 0,
                    style: {
                        textShadow: "1px 0 1px #333333"
                    },
                },
            });
            graph.addCell(entityCustome);
        });



        // Add Attribute
        let btnAddAttribute = document.getElementById("btnAddAttribute");
        btnAddAttribute.addEventListener("click", function() {
            $('#input_wrapper1').show();
        });
        $('#addAttribute').on('click', function() {
            let attribute = new joint.shapes.standard.Ellipse();
            attribute.position(100, 20);
            attribute.resize(100, 40);
            attribute.attr({
                body: {
                    fill: 'orange',
                },
                label: {
                    text: $('#nameAttribute').val(),
                    fill: 'black',
                    letterSpacing: 0,
                    style: {
                        textShadow: "1px 0 1px #333333"
                    },
                }
            });
            graph.addCell(attribute);
        });

        //Key Attribute
        let btnAddCustomAtribut = document.getElementById("btnAddCustomAtribut");
        btnAddCustomAtribut.addEventListener("click", function() {
            $('#input_wrapper3').show();
        });
        $('#addCustomAtribut').on('click', function() {
            let attribute = new joint.shapes.standard.Ellipse();
            attribute.position(100, 150);
            attribute.resize(100, 40);
            attribute.attr({
                body: {
                    fill: 'orange',
                },
                label: {
                    text: $('#nameCustomAtribut').val(),
                    fill: 'White',
                    letterSpacing: 0,
                    style: {
                        textShadow: "1px 0 1px #333333",
                        textDecoration: "underline"
                    },
                }
            });
            graph.addCell(attribute);
        });

        //Composite Attribute
        let btnAddComposite = document.getElementById("btnAddComposite");
        btnAddComposite.addEventListener("click", function() {
            $('#input_wrappercomposite').show();
        });
        $('#addattributeComposite').on('click', function() {
            let attribute = new joint.shapes.standard.Ellipse();
            attribute.position(100, 200);
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

        //Derived Attribute
        let btnAddDerived = document.getElementById("btnAddDerived");
        btnAddDerived.addEventListener("click", function() {
            $('#input_wrapperderived').show();
        });
        $('#addattributeDerived').on('click', function() {
            let attribute = new joint.shapes.standard.Ellipse();
            attribute.position(100, 300);
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
            attribute.position(100, 400);
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
    </script>
@endpush