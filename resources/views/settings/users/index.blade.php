@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h5 class="card-title fw-semibold mb-1">Daftar Users</h5>
                            <p class="card-subtitle mb-0">Kelola user</p>
                        </div>
                        <div
                            class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 flex-shrink-0">
                            <button type="button" class="btn btn-primary d-flex align-items-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#addMenuModal" id="addUser">
                                <i class="ti ti-plus"></i>
                                <span>Tambah user</span>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0" id="frontTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Company</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
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
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-semibold" id="addUserModalLabel">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <input type="text" id="id" hidden>
                                <label for="name" class="form-label">Company</label>
                                <select class="form-select" id="company_id" name="company_id">
                                </select>
                            </div>
                            <div class="mb-3 col-md-6" id="d_icon">
                                <label for="icon" class="form-label">Role</label>
                                <select class="form-select" id="role_id" name="role_id">
                                    <option>Pilih Role</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Nama lengkap</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" required>
                            </div>
                            <div class="mb-3 col-md-6" id="d_url">
                                <label for="url" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="{{ old('username') }}">
                            </div>
                            <div class="mb-3 col-md-6" id="d_url">
                                <label for="url" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}">
                            </div>
                            <div class="mb-3 col-md-6" id="d_url">
                                <label for="url" class="form-label">Password (default = username)</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="icon" class="form-label">Is Active</label>
                                <select class="form-select" id="is_active" name="is_active">
                                    <option value="1">Active</option>
                                    <option value="0">Not Active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" id="d_submit_user">
                        {{-- <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button> --}}
                        <button type="submit" class="btn btn-primary" id="submit_user">Simpan User</button>
                    </div>
                    <div id="modalMenuAccess" class="d-none">
                        <div class="modal-header d-flex align-items-center">
                            <h5 class="modal-title fw-semibold mb-0" id="addMenuModalLabel">
                                Tambah Menu
                            </h5>

                            <div class="ms-auto" style="width: 180px;">
                                <select class="form-select form-select-sm" id="menu_access" name="menu_access">
                                </select>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive mb-3">
                                <table class="table table-hover align-middle text-nowrap mb-0" id="menuAccessTbl">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Level</th>
                                            <th>Menu</th>
                                            <th>Parent</th>
                                            <th>Icon</th>
                                            <th>Url</th>
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
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            frontTable()
        })
        $('#company_id').select2({
            placeholder: 'Pilih Company',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#addMenuModal'),

            ajax: {
                url: "{{ url('settings/users/user/company_id') }}",
                dataType: 'json',
                type: 'POST',
                delay: 250,

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: function(params) {
                    return {
                        search: params.term || ''
                    };
                },

                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.company_name
                            };
                        })
                    };
                }
            }
        });
        $('#role_id').select2({
            placeholder: 'Pilih role',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#addMenuModal'),

            ajax: {
                url: "{{ url('settings/users/user/role_id') }}",
                dataType: 'json',
                type: 'POST',
                delay: 250,

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: function(params) {
                    return {
                        search: params.term || ''
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
        $("#submit_user").on('click', function() {
            const id = $("#id").val();
            const url = id ?
                "{{ url('settings/users/user/update') }}" :
                "{{ url('settings/users/user/store') }}";
            let data = {
                _token: "{{ csrf_token() }}",
                company_id: $("#company_id").val(),
                role_id: $("#role_id").val(),
                name: $("#name").val(),
                username: $("#username").val(),
                email: $("#email").val(),
                password: $("#password").val(),
                is_active: $("#is_active").val()
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
                        $("#d_submit_user").addClass('d-none')
                        $("#modalMenuAccess").removeClass('d-none')
                        $("#id").val(res.id)
                        menuAccess();
                        menuAccessData()
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

        function menuAccess() {
            $("#menu_access").select2({
                placeholder: 'Pilih menu',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#addMenuModal'),

                ajax: {
                    url: "{{ url('settings/users/user/menu_list') }}",
                    dataType: 'json',
                    type: 'POST',
                    delay: 250,

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    data: function(params) {
                        return {
                            search: params.term || ''
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
        }
        $("#menu_access").on('change', function() {
            $.ajax({
                url: "{{ url('settings/users/user/store_menu_access') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    user_id: $("#id").val(),
                    menu_id: $(this).val()
                },
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
                        $("#menuAccessTbl").DataTable().ajax.reload()
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
        })

        function menuAccessData() {
            const id = $("#id").val()
            if ($.fn.DataTable.isDataTable('#menuAccessTbl')) {
                $('#menuAccessTbl').DataTable().destroy();
            }
            $('#menuAccessTbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('settings/users/user/menu_access') }}",
                    type: 'POST',
                    data: function(d) {
                        d._token = "{{ csrf_token() }}",
                            d.user_id = id
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'level',
                        name: 'level'
                    }, {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'parent_id',
                        name: 'parent_id'
                    },
                    {
                        data: 'icon',
                        name: 'icon',
                        defaultContent: '-'
                    },
                    {
                        data: 'url',
                        name: 'url'
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

        function frontTable() {
            $('#frontTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('settings/users/user/front_table') }}",

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'company',
                        name: 'company'
                    }, {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'username',
                        name: 'username'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        defaultContent: '-'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

        function deleteMenuAccess(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('settings/users/user/delete_menu_access') }}",
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
                                    icon: 'success',
                                    title: res.message,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                                $("#menuAccessTbl").DataTable().ajax.reload()
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
            });
        }

        function editUser(id) {
            $.ajax({
                url: "{{ url('settings/users/user/show') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    user_id: id
                },
                success: function(res) {
                    if (res.status === true) {
                        const data = res.data
                        $("#name").val(data.name)
                        $("#username").val(data.username)
                        $("#email").val(data.email)
                        if (data.role) {
                            const roleOption = new Option(
                                data.role.name,
                                data.role.id,
                                true,
                                true
                            );
                            $("#role_id")
                                .append(roleOption)
                                .trigger("change");
                        }
                        if (data.company) {
                            const companyOption = new Option(
                                data.company.company_name,
                                data.company.id,
                                true,
                                true
                            );

                            $("#company_id")
                                .append(companyOption)
                                .trigger("change");
                        }
                        $("#is_active")
                            .val(data.is_active)
                            .trigger("change");

                        $("#addUserModalLabel").text('Edit User');
                        $("#addMenuModal").modal('show');
                        $("#modalMenuAccess").removeClass('d-none')
                        $("#id").val(data.id)
                        menuAccess();
                        menuAccessData()
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
        $("#addUser").on('click', function() {
            $("#name").val(null)
            $("#username").val(null)
            $("#email").val(null)
            $("#password").val(null)
            $("#id").val(null)
            $("#addUserModalLabel").text('Tambah User');
            $("#modalMenuAccess").addClass('d-none')
        })

        function deleteUser(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('settings/users/user/delete') }}",
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
                                    icon: 'success',
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
                }
            });
        }
    </script>
@endsection
