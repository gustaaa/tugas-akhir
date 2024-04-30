@extends('layouts.dashboard')

@section('content')
<!-- Main Content -->
<section class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Data User</h4>
        <div class="float-right">
            <button id="showFormButton" class="btn btn-primary active import d-inline">
                <i class="fa fa-fw fa-download" aria-hidden="true"></i> Import Excel
            </button>
            <a href="{{ route('user.export') }}" class="btn btn-info btn-primary active btn-rounded d-inline">
                <i class="fa fa-upload" aria-hidden="true"></i> Export Data</a>
            <a href="{{ route('user.create') }}" class="btn btn-primary d-inline">
                <i class="fas fa-fw fa-plus-circle"></i> Tambah Data
            </a>
            <form id="clearForm" action="#" method="POST" class="d-inline">
                @csrf
                <button id="clearDataButton" class="btn btn-danger ml-2" type="submit">
                    <i class="fa fa-trash" aria-hidden="true"></i> Clear Data
                </button>
            </form>
        </div>

        <div id="formContainer" style="display: none;">
            <form action="/data-import" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file">
                <input type="submit" value="Import">
            </form>
        </div>

        <script>
            var showFormButton = document.getElementById('showFormButton');
            var formContainer = document.getElementById('formContainer');

            showFormButton.addEventListener('click', function() {
                showFormButton.style.display = 'none';
                formContainer.style.display = 'block';
            });

            var clearForm = document.getElementById('clearForm');
            clearForm.addEventListener('submit', function(event) {
                event.preventDefault();

                var confirmation = confirm("Apakah Anda yakin ingin menghapus data?");

                if (confirmation) {
                    fetch("#", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            });
        </script>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-md">
                <tbody>
                    <tr>
                        <th>#</th>
                        <th data-id="thName">Name</th>
                        <th data-id="thUsername">Username</th>
                        <th data-id="thEmail">Email</th>
                        <th data-id="thRole">Role</th>
                        <th class="text-center">Action</th>
                    </tr>
                    @foreach ($users as $key => $user)
                    <tr>
                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $key + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email}}</td>
                        <td>{{ $user->role}}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('user.show', $user->id) }}" data-id="viewUser31" class="btn btn-success btn-icon btn-sm mr-2">
                                    <i class="fas fa-eye"></i>
                                    View
                                </a>
                                <a href="{{ route('user.edit', $user->id) }}" data-id="editUser31" class="btn btn-sm btn-info btn-icon mr-2">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button data-id="deleteUser31" class="btn btn-sm btn-danger btn-icon mr-2">
                                        <i class="fas fa-times"></i>
                                        Delete
                                    </button>
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
    });
</script>
@endpush

@push('customStyle')
@endpush