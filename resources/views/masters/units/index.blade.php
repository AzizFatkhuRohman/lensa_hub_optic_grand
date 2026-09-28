@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h5 class="card-title fw-semibold mb-1">Data Unit</h5>
                            <p class="card-subtitle mb-0">Kelola data satuan produk</p>
                        </div>
                        <button type="button" class="btn btn-primary d-flex align-items-center gap-2 flex-shrink-0"
                            id="btnAddUnit" data-bs-toggle="modal" data-bs-target="#unitModal">
                            <i class="ti ti-plus"></i>
                            <span>Tambah Unit</span>
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0" id="unitTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Unit</th>
                                    <th>Nama Unit</th>
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

    <div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="unitModalLabel">Tambah Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="unitId">

                    <div class="mb-3">
                        <label for="unit_code" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="unit_code" maxlength="50" placeholder="Contoh: PCS">
                    </div>

                    <div class="mb-3">
                        <label for="unit_name" class="form-label">Nama Unit <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="unit_name" maxlength="100"
                            placeholder="Contoh: Pieces">
                    </div>

                    <div class="mb-0">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" rows="3" placeholder="Masukkan deskripsi unit"></textarea>
                    </div>

                    <div class="mt-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status">
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnSaveUnit">
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
            if ($.fn.DataTable.isDataTable('#unitTable')) {
                $('#unitTable').DataTable().destroy();
            }
            $('#unitTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('masters/products/unit/front_table') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'unit_code',
                        name: 'unit_code'
                    },
                    {
                        data: 'unit_name',
                        name: 'unit_name'
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
        $('#btnAddUnit').on('click', function() {
            clearUnitForm();
            $('#unitModalLabel').text('Tambah Unit');
            bootstrap.Modal.getOrCreateInstance(document.getElementById('unitModal')).show();
        });

        function clearUnitForm() {
            $('#unitId, #unit_code, #unit_name, #description').val('');
            $('#status').val('active');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }

        function showUnitErrors(errors) {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.each(errors, function(field, messages) {
                const input = $('#' + field);
                input.addClass('is-invalid');
                input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
            });
        }

        function saveUnit() {
            const id = $('#unitId').val();
            const button = $('#btnSaveUnit');
            const originalButton = '<i class="ti ti-device-floppy me-1"></i> Simpan';

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

            $.ajax({
                url: id ? "{{ url('masters/products/unit/update') }}/" + id :
                    "{{ url('masters/products/unit/store') }}",
                type: id ? 'PUT' : 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    unit_code: $('#unit_code').val(),
                    unit_name: $('#unit_name').val(),
                    description: $('#description').val(),
                    status: $('#status').val()
                },
                success: function(response) {
                    if (!response.status) {
                        return;
                    }

                    bootstrap.Modal.getOrCreateInstance(document.getElementById('unitModal')).hide();
                    unitTable.ajax.reload(null, false);
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
                        showUnitErrors(xhr.responseJSON.errors);
                        return;
                    }

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: xhr.responseJSON?.message || 'Unit gagal disimpan.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                },
                complete: function() {
                    button.prop('disabled', false).html(originalButton);
                }
            });
        }

        function editUnit(id) {
            $.get("{{ url('masters/products/unit/edit') }}/" + id, function(response) {
                if (!response.status) {
                    return;
                }

                clearUnitForm();
                $('#unitId').val(response.data.id);
                $('#unit_code').val(response.data.unit_code);
                $('#unit_name').val(response.data.unit_name);
                $('#description').val(response.data.description);
                $('#status').val(response.data.status);
                $('#unitModalLabel').text('Edit Unit');
                bootstrap.Modal.getOrCreateInstance(document.getElementById('unitModal')).show();
            }).fail(function(xhr) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: xhr.responseJSON?.message || 'Data unit gagal diambil.',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        }

        function deleteUnit(id) {
            Swal.fire({
                title: 'Hapus Unit?',
                text: 'Data unit yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: "{{ url('masters/products/unit/destroy') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        unitTable.ajax.reload(null, false);
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
                            title: xhr.responseJSON?.message || 'Unit gagal dihapus.',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });
        }
    </script>
@endsection
