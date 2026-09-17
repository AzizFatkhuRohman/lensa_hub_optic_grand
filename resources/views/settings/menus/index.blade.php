@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h5 class="card-title fw-semibold mb-1">Daftar Menu</h5>
                            <p class="card-subtitle mb-0">Kelola menu navigasi aplikasi</p>
                        </div>
                        <div
                            class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 flex-shrink-0">
                            <span class="badge bg-light-primary text-primary">menu</span>
                            <button type="button" class="btn btn-primary d-flex align-items-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#addMenuModal">
                                <i class="ti ti-plus"></i>
                                <span>Tambah Menu</span>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0" id="frontTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Level</th>
                                    <th>Parent</th>
                                    <th>URL</th>
                                    <th>Icon</th>
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
                        <h5 class="modal-title fw-semibold" id="addMenuModalLabel">Tambah Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="text" id="id" hidden>
                            <label for="level" class="form-label">Level</label>
                            <select class="form-select" id="level" name="level" required>
                                <option value="1" @selected(old('level', '1') == '1')>Level 1</option>
                                <option value="2" @selected(old('level') == '2')>Level 2</option>
                                <option value="3" @selected(old('level') == '3')>Level 3</option>
                            </select>
                        </div>
                        <div class="mb-3 d-none" id="d_parent">
                            <label for="parent_id" class="form-label">Parent</label>
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="">Tanpa parent</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Menu</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3 d-none" id="d_icon">
                            <label for="icon" class="form-label">Icon</label>
                            <select class="form-select" id="icon" name="icon">
                                <option>Pilih Icon</option>
                                <option value="ti ti-map-alt" @selected(old('icon') == 'ti ti-map-alt')>Location</option>
                                <option value="ti ti-circle" @selected(old('icon', 'ti ti-circle') == 'ti ti-circle')>Circle</option>
                                <option value="ti ti-menu" @selected(old('icon') == 'ti ti-menu')>Menu</option>
                                <option value="ti ti-dashboard" @selected(old('icon') == 'ti ti-dashboard')>Dashboard</option>
                                <option value="ti ti-home" @selected(old('icon') == 'ti ti-home')>Home</option>
                                <option value="ti ti-users" @selected(old('icon') == 'ti ti-users')>Users</option>
                                <option value="ti ti-user" @selected(old('icon') == 'ti ti-user')>User</option>
                                <option value="ti ti-settings" @selected(old('icon') == 'ti ti-settings')>Settings</option>
                                <option value="ti ti-package" @selected(old('icon') == 'ti ti-package')>Product</option>
                                <option value="ti ti-file" @selected(old('icon') == 'ti ti-file')>File</option>
                                <option value="ti ti-report" @selected(old('icon') == 'ti ti-report')>Report</option>
                            </select>
                        </div>
                        <div class="mb-3 d-none" id="d_url">
                            <label for="url" class="form-label">URL</label>
                            <input type="text" class="form-control" id="url" name="url"
                                value="{{ old('url') }}" placeholder="settings/example">
                        </div>
                        <div class="mb-0">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submit_menu">Simpan Menu</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-semibold" id="editMenuModalLabel">Edit Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editLevel" class="form-label">Level</label>
                            <select class="form-select" id="editLevel" name="level" required>
                                <option value="1">Level 1</option>
                                <option value="2">Level 2</option>
                                <option value="3">Level 3</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editParentId" class="form-label">Parent</label>
                            <select class="form-select" id="editParentId" name="parent_id">
                                <option value="">Tanpa parent</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nama Menu</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editIcon" class="form-label">Icon</label>
                            <select class="form-select" id="editIcon" name="icon">
                                <option value="ti ti-circle">Circle</option>
                                <option value="ti ti-menu">Menu</option>
                                <option value="ti ti-dashboard">Dashboard</option>
                                <option value="ti ti-home">Home</option>
                                <option value="ti ti-users">Users</option>
                                <option value="ti ti-user">User</option>
                                <option value="ti ti-settings">Settings</option>
                                <option value="ti ti-package">Product</option>
                                <option value="ti ti-file">File</option>
                                <option value="ti ti-report">Report</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editUrl" class="form-label">URL</label>
                            <input type="text" class="form-control" id="editUrl" name="url"
                                placeholder="settings/example">
                        </div>
                        <div class="mb-0">
                            <label for="editDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="submit_menu">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <script>
        $(document).ready(function() {
            frontTable()
        })
        $('#parent_id').select2({
            placeholder: 'Pilih parent',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#addMenuModal'),

            ajax: {
                url: "{{ url('settings/menus/parent_id') }}",
                dataType: 'json',
                type: 'POST',
                delay: 250,

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: function(params) {
                    return {
                        search: params.term || '',
                        level: $('#level').val()
                    };
                },

                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        })
                    };
                }
            }
        });
        $('#level').on('change', function() {
            let level = $(this).val();
            $('#parent_id')
                .val(null)
                .trigger('change');
            if (level == 1) {
                $("#d_parent").addClass('d-none')
                $('#parent_id').prop('disabled', true);
                $("#d_icon").addClass('d-none')
                $("#d_url").addClass('d-none')
            } else {
                $("#d_parent").removeClass('d-none')
                $('#parent_id').prop('disabled', false);
                $("#d_icon").removeClass('d-none')
                $("#d_url").removeClass('d-none')
            }
        });
        $('#addMenuModal').on('shown.bs.modal', function() {
            $('#level').trigger('change');
        });
        $("#submit_menu").on('click', function() {
            const id = $("#id").val();
            const url = id ?
                "{{ url('settings/menus/update') }}" :
                "{{ url('settings/menus/store') }}";
            let data = {
                _token: "{{ csrf_token() }}",
                level: $("#level").val(),
                parent_id: $("#parent_id").val(),
                name: $("#name").val(),
                icon: $("#icon").val(),
                url: $("#url").val(),
                description: $("#description").val()
            };

            if (id) {
                data.id = id;
            }
            $.ajax({
                url: url,
                type: 'POST',
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

        function frontTable() {
            $('#frontTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('settings/menus/front_table') }}",

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'level',
                        name: 'level'
                    },
                    {
                        data: 'parent_name',
                        name: 'parent_name',
                        defaultContent: '-'
                    },
                    {
                        data: 'url',
                        name: 'url'
                    },
                    {
                        data: 'icon',
                        name: 'icon',
                        searchable: false,
                        className: 'text-end'
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

        function editMenu(id) {
            $.ajax({
                url: "{{ url('settings/menus/show') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(res) {
                    const data = res.data;
                    $("#id").val(data.id);
                    $("#name").val(data.name);
                    $("#url").val(data.url);
                    $("#description").val(data.description);
                    $("#icon").val(data.icon).trigger('change');

                    $("#level").val(data.level);

                    if (data.level == 1) {
                        $("#d_parent").addClass('d-none');
                        $("#parent_id").prop('disabled', true);
                        $("#parent_id").val(null).trigger('change');

                        $("#d_icon").addClass('d-none');
                        $("#d_url").addClass('d-none');
                    } else {
                        $("#d_parent").removeClass('d-none');
                        $("#parent_id").prop('disabled', false);

                        $("#d_icon").removeClass('d-none');
                        $("#d_url").removeClass('d-none');

                        $("#parent_id")
                            .empty()
                            .append('<option value="">Tanpa parent</option>');

                        if (data.parent_id !== null) {
                            $.ajax({
                                url: "{{ url('settings/menus/parent_id') }}",
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    search: '',
                                    level: data.level
                                },
                                success: function(parents) {
                                    const parent = parents.find(function(item) {
                                        return String(item.id) === String(data.parent_id);
                                    });

                                    if (parent) {
                                        const option = new Option(
                                            parent.name,
                                            parent.id,
                                            true,
                                            true
                                        );

                                        $("#parent_id")
                                            .append(option)
                                            .trigger('change');
                                    }
                                }
                            });
                        }
                    }

                    $("#addMenuModalLabel").text('Edit Menu');
                    $("#addMenuModal").modal('show');
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
            });
        }

        function deleteMenu(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed)
                    $.ajax({
                        url: "{{ url('settings/menus/delete') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id
                        },
                        success: function(res) {
                            if (res.status === true) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: res.message,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                                $('#frontTable').DataTable().ajax.reload()
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
            });
        }
    </script>
@endsection
