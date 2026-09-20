@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h5 class="card-title fw-semibold mb-1">Daftar Perusahaan</h5>
                            <p class="card-subtitle mb-0">Kelola perusahaan</p>
                        </div>
                        <div
                            class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 flex-shrink-0">
                            <button type="button" class="btn btn-primary d-flex align-items-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#addMenuModal" id="btnAddModal">
                                <i class="ti ti-plus"></i>
                                <span>Tambah</span>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0" id="frontTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-semibold" id="addMenuModalLabel">Tambah perusahaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="text" id="id" hidden>
                            <label for="name" class="form-label">Nama Perusahaan</label>
                            <input type="text" class="form-control" id="company_name" name="company_name"
                                value="{{ old('company_name') }}" required>
                        </div>
                        <div class="mb-0">
                            <label for="description" class="form-label">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="simpan">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            frontTable()
        })

        function frontTable() {
            if ($.fn.DataTable.isDataTable('#frontTable')) {
                $('#frontTable').DataTable().destroy();
            }
            $("#frontTable").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('settings/company/front_table') }}",
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'company_name',
                    name: 'company_name'
                }, {
                    data: 'address',
                    name: 'address'
                }, {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }]
            })
        }
        $("#btnAddModal").on('click', function() {
            $("#id").val(null)
            $("#company_name").val(null)
            $("#address").val(null)
            $("#addMenuModalLabel").text('Tambah Perusahaan');
        })
        $("#simpan").on('click', function() {
            const id = $("#id").val()
            let data = {
                '_token': "{{ csrf_token() }}",
                'company_name': $("#company_name").val(),
                'address': $("#address").val()
            }
            if (id) {
                data.id = id
            }
            const url = id ?
                "{{ url('settings/company/update') }}" :
                "{{ url('settings/company/store') }}";
            $.ajax({
                url: url,
                type: "POST",
                data: data,
                success: function(res) {
                    if (res.status === true) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                        $('#frontTable').DataTable().ajax.reload()
                    } else {
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();

                        // Tampilkan error validasi
                        $.each(res.message, function(field, errors) {
                            let input = $('#' + field);

                            input.addClass('is-invalid');

                            input.after(
                                '<div class="invalid-feedback">' +
                                errors[0] +
                                '</div>'
                            );
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: xhr.responseJSON.message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }
            })
        })

        function editCompany(id) {
            $.ajax({
                url: "{{ url('settings/company/show') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(res) {
                    if (res.status === true) {
                        const data = res.data;
                        $("#id").val(data.id)
                        $("#company_name").val(data.company_name)
                        $("#address").val(data.address)
                        $("#addMenuModalLabel").text('Edit Perusahaan');
                        $("#addMenuModal").modal('show');
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: xhr.responseJSON.message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }
            })
        }
    </script>
@endsection
