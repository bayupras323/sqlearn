@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible show fade">
        <div class="alert-body" >
            <button class="close" data-dismiss="alert" id="close_btn">
                <span>×</span>
            </button>
            <p data-id="success_alert">{{ $message }} </p>
        </div>
    </div>
@endif
<!-- dont change it bruh : aqil -->
@if ($message = Session::get('alert-user'))
    <div class="alert alert-{{$message['type']}} alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert" id="close_btn">
                <span>×</span>
            </button>
            <p data-id="alert">
                <?php echo $message['content']?>
            </p>
        </div>
    </div>
@endif
<!-- dont change it bruh : aqil -->
@if ($message = Session::get('alert'))
    <div class="alert alert-danger alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert" id="close_btn">
                <span>×</span>
            </button>
            <p data-id="alert">
                <?php echo $message?>
            </p>
        </div>
    </div>
@endif

