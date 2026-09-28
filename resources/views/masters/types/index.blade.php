@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h5 class="card-title fw-semibold mb-1">Data Type</h5>
                            <p class="card-subtitle mb-0">Kelola tipe produk</p>
                        </div>
                        <button type="button" class="btn btn-primary d-flex align-items-center gap-2 flex-shrink-0"
                            data-bs-toggle="modal" data-bs-target="#typeModal" id="btnAddType">
                            <i class="ti ti-plus"></i>
                            <span>Tambah Type</span>
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0" id="typeTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Type</th>
                                    <th>Nama Type</th>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="typeModal" tabindex="-1" aria-labelledby="typeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="typeModalLabel">Tambah Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="typeId">

                    <div class="mb-3">
                        <label for="type_code" class="form-label">Kode Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="type_code" maxlength="50" placeholder="Contoh: FRM">
                    </div>

                    <div class="mb-3">
                        <label for="type_name" class="form-label">Nama Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="type_name" maxlength="100"
                            placeholder="Contoh: Frame">
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id">
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" rows="3" placeholder="Masukkan deskripsi type"></textarea>
                    </div>

                    <div class="mb-0">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status">
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnSaveType">
                        <i class="ti ti-device-floppy me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            frontTable()
        })

        function frontTable() {
            if ($.fn.DataTable.isDataTable('#typeTable')) {
                $('#typeTable').DataTable().destroy();
            }
            $('#typeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('masters/products/type/front_table') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'type_code',
                        name: 'type_code'
                    },
                    {
                        data: 'type_name',
                        name: 'type_name'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        defaultContent: '-'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            return data === 'active' ?
                                '<span class="badge bg-success-subtle text-success">Aktif</span>' :
                                '<span class="badge bg-danger-subtle text-danger">Tidak Aktif</span>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-end'
                    }
                ]
            });
        }
        $('#btnAddType').on('click', function() {
            clearTypeForm();
            $('#typeModalLabel').text('Tambah Type');
            bootstrap.Modal.getOrCreateInstance(document.getElementById('typeModal')).show();
        });

        function clearTypeForm() {
            $('#typeId, #type_code, #type_name, #description').val('');
            $('#category_id').val('');
            $('#status').val('active');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }

        function showTypeErrors(errors) {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.each(errors, function(field, messages) {
                const input = $('#' + field);
                input.addClass('is-invalid');
                input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
            });
        }

        function saveType() {
            const id = $('#typeId').val();
            const button = $('#btnSaveType');
            const originalText = '<i class="ti ti-device-floppy me-1"></i> Simpan';

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.ajax({
                url: id ? "{{ url('masters/products/type/update') }}" : "{{ url('masters/products/type/store') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id || undefined,
                    type_code: $('#type_code').val(),
                    type_name: $('#type_name').val(),
                    category_id: $('#category_id').val(),
                    description: $('#description').val(),
                    status: $('#status').val()
                },
                success: function(response) {
                    if (!response.status) {
                        return;
                    }

                    bootstrap.Modal.getOrCreateInstance(document.getElementById('typeModal')).hide();
                    typeTable.ajax.reload(null, false);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2500
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        showTypeErrors(xhr.responseJSON.errors);
                        return;
                    }

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: xhr.responseJSON?.message || 'Type gagal disimpan.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                },
                complete: function() {
                    button.prop('disabled', false).html(originalText);
                }
            });
        }

        function editType(id) {
            $.ajax({
                url: "{{ url('masters/produtcts/type/show') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(response) {
                    if (!response.status) {
                        return;
                    }

                    clearTypeForm();
                    $('#typeId').val(response.data.id);
                    $('#type_code').val(response.data.type_code);
                    $('#type_name').val(response.data.type_name);
                    $('#category_id').val(response.data.category_id);
                    $('#description').val(response.data.description);
                    $('#status').val(response.data.status);
                    $('#typeModalLabel').text('Edit Type');
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('typeModal')).show();
                },
                error: function(xhr) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: xhr.responseJSON?.message || 'Data type gagal diambil.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        }

        function deleteType(id) {
            Swal.fire({
                title: 'Hapus Type?',
                text: 'Data type yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: "{{ url('masters/products/type/delete') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(response) {
                        typeTable.ajax.reload(null, false);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: response.status ? 'success' : 'error',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: xhr.responseJSON?.message || 'Type gagal dihapus.',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });
        }
    </script>
@endsection
