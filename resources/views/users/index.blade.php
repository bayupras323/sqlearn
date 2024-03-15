@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>User List</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Components</a></div>
                <div class="breadcrumb-item">Table</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">User Management</h2>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>User List</h4>
                            <div class="card-header-action">
                                <a class="btn btn-icon icon-left btn-primary" href="{{ route('user.create') }}">Create New
                                    User</a>
                                <a class="btn btn-info btn-primary active import" data-id="import-user">
                                    <i class="fa fa-upload" aria-hidden="true"></i>
                                    Import User</a>
                                <a class="btn btn-info btn-primary active" href="{{ route('user.export') }}" data-id="export-user">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    Export User</a>
                                <a class="btn btn-info btn-primary active search" data-id="search-user">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    Search User</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="show-import" style="display: none">
                                <div class="custom-file">
                                    <form action="{{ route('user.import') }}" method="post" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <label class="custom-file-label" for="file-upload" data-id="file-upload-import-label">Choose File</label>
                                        <input type="file" id="file-upload" data-id="file-upload-import" class="custom-file-input" name="import_file" required>
                                        <br /> <br />
                                        <div class="footer text-right">
                                            <a style="cursor: pointer;" title="If checkbox has checked & if in the import file there is an email that has been registered then it will be updated according to the data entered, and for addition info if password column is empty default value stored is 12345678"> Hover me, More Info ! <i class="fa fa-info"></i></a>
                                            &nbsp;
                                             <input type="checkbox" data-id="file-upload-import-update-exisiting" name="update-existing" class=""> Update Existing user
                                            <button class="btn btn-primary" data-id="fix-import-file-user"><i class="fa fa-upload"></i> Import File</button>
                                            &nbsp;
                                             <a class="btn btn-success"  href="{{ url('assets/import/users_template.xlsx') }}" 
                                             data-id="download-user-template-import" download>
                                             <i class="fa fa-file-excel"></i> Download Template</a>
                                        </div>
                                    </form>
                                </div>
                                <hr>
                            </div>
                            <div class="show-search mb-3" style="display: {{$request->name != NULL?'':'none'}}">
                                <form id="search" method="GET" action="{{ route('user.index') }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="role">User</label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                placeholder="User Name" data-id="search-user-by-name" value="{{$request->name}}">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary mr-1" type="submit">Submit</button>
                                        <a class="btn btn-secondary" href="{{ route('user.index') }}" data-id="reset-search-user">Reset</a>
                                    </div>
                                </form>
                                <hr>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tbody>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                        @foreach ($users as $key => $user)
                                            <tr>
                                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $key + 1 }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email}}</td>
                                                <td>{{ $user->created_at}}</td>
                                                <td>{{ $user->updated_at}}</td>
                                                <td class="text-right">
                                                    <div class="d-flex justify-content-end">
                                                        <a href="{{ route('user.edit', $user->id) }}"
                                                            class="btn btn-sm btn-info btn-icon "><i
                                                                class="fas fa-edit"></i>
                                                            Edit</a>
                                                        <form action="{{ route('user.destroy', $user->id) }}"
                                                            method="POST" class="ml-2">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}">
                                                            <button class="btn btn-sm btn-danger btn-icon" onclick="return confirm('Apakah yakin untuk menghapus?')">
                                                                <i class="fas fa-times"></i> Delete </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $users->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>




                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('customScript')
    <script>
        $(document).ready(function() {
            $('.import').click(function(event) {
                event.stopPropagation();
                $(".show-import").slideToggle("fast");
                $(".show-search").hide();
            });
            $('.search').click(function(event) {
                event.stopPropagation();
                $(".show-search").slideToggle("fast");
                $(".show-import").hide();
            });
            //ganti label berdasarkan nama file
            $('#file-upload').change(function() {
                var i = $(this).prev('label').clone();
                var file = $('#file-upload')[0].files[0].name;
                $(this).prev('label').text(file);
            });
            @if ($message = Session::get('alert'))
                setTimeout(removeAlert, 3000);
            @endif
        });

        function removeAlert() 
        {
            $('#close_btn').trigger('click');
        }
    </script>
@endpush

@push('customStyle')
@endpush
