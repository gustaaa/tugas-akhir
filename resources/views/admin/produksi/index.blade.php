@extends('layouts.dashboard')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Data Produksi</h4>
        <div class="float-right">
            <button id="showFormButton" class="btn btn-primary active import d-inline">
                <i class="fa fa-fw fa-download" aria-hidden="true"></i> Import Excel
            </button>
            <a href="{{ route('produksi.export') }}" class="btn btn-info btn-primary active btn-rounded d-inline">
                <i class="fa fa-upload" aria-hidden="true"></i> Export Data</a>
            <a href="{{ route('produksi.create') }}" class="btn btn-primary d-inline">
                <i class="fas fa-fw fa-plus-circle"></i> Tambah Data
            </a>
            <form id="clearForm" action="{{ route('produksi.clear') }}" method="POST" class="d-inline">
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
                    fetch("{{ route('produksi.clear') }}", {
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
            <table class="table table-bordered" id="example" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="color: navy;">No</th>
                        <th style="color: navy;">Tebu Masuk</th>
                        <th style="color: navy;">Rendemen Tebu</th>
                        <th style="color: navy;">Luas Lahan</th>
                        <th style="color: navy;">Produktivitas</th>
                        <th style="color: navy;">Produksi Gula</th>
                        <th style="color: navy;" width="100px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $no = 1;
                    @endphp
                    @foreach ($mapel as $key => $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item->C1 }}</td>
                        <td>{{ $item->C2 }}</td>
                        <td>{{ $item->C3 }}</td>
                        <td>{{ $item->C4 }}</td>
                        <td>{{ $item->C5 }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('produksi.edit', $item->id) }}" class="btn bg-gradient-success btn-sm text-white"><i class="fas fa-fw fa-edit"></i></a>
                                <form action="{{ route('produksi.destroy', $item->id) }}" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button data-id="deleteUser31" class="btn btn-sm btn-danger btn-icon mr-2">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-40 col-sm-12 text-center">
            <br>
            {{$mapel->links()}}
        </div>
    </div>
</div>
@endsection