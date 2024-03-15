<div class="col-md-12 sm-12">
    <div class="card ">
        <div class="card-body pt-0">
            <h2 class="section-title">Parsons Problem 2D</h2>
            <div id="sortableTrash" class="sortable-code"></div>
            <div id="sortable" class="sortable-code">
            </div>
            <div style="clear:both;"></div>
            <div class="row mt-1">
                <div class="col-md-12 text-right">
                    <button class="btn btn-primary waves-effect waves-light" id="saveButton">Lanjut</button>
                </div>
            </div>

        </div>
    </div>
</div>

@push('customScript')
    <script>
        var initial = '<?= $encodeAnswer ?>';

        var parson;

        parson = new ParsonsWidget({
            'sortableId': 'sortable',
            'trashId': 'sortableTrash',
            'max_wrong_lines': 1,
            'vartests': [],
            'grader': ParsonsWidget._graders.LanguageTranslationGrader
        });
        parson.init(initial);
        parson.shuffleLines();
        $("#saveButton").click(function(event) {
            event.preventDefault();
            var answer = $("#sortable")[0].innerText;
            answer = answer.replace("ganerate parsosn problem", "");
            answer = answer.replace(/\n/g, ' ').replace(/  +/g, ' ').trim();
            answer = answer.replace(/([{}])/g, '$1\n');
            Livewire.emit('updateAnswer', answer);
        });
    </script>
@endpush
