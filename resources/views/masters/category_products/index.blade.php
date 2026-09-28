@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h5 class="card-title fw-semibold mb-1">Data Kategori Produk</h5>
                            <p class="card-subtitle mb-0">Kelola kategori produk</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 flex-shrink-0">
                            <button type="button" class="btn btn-primary d-flex align-items-center gap-2" id="btnAddCategoryProduct">
                                <i class="ti ti-plus"></i>
                                <span>Tambah Kategori</span>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0" id="frontTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Kategori</th>
                                    <th>Keterangan</th>
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

    {{-- Modal --}}
    <div
        class="modal fade"
        id="categoryProductModal"
        tabindex="-1"
        aria-labelledby="categoryProductModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                {{-- Modal Header --}}
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="categoryProductModalLabel">
                        Tambah Kategori Produk
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                {{-- Modal Body --}}
                <div class="modal-body">
                    <input type="hidden" id="id">
                    {{-- Category Code --}}
                    <div class="mb-3">
                        <label for="category_code" class="form-label"> Kode Kategori</label>
                        <input type="text" class="form-control" id="category_code" name="category_code" maxlength="50" placeholder="Contoh: FRM" required>
                    </div>

                    {{-- Category Name --}}
                    <div class="mb-3">
                        <label for="category_name"cclass="form-label">
                            Nama Kategori
                        </label>
                        <input type="text" class="form-control" id="category_name" name="category_name" maxlength="100" placeholder="Contoh: Frame" required>
                    </div>

                    {{-- Description --}}
                    <div class="mb-0">
                        <label for="description" class="form-label">
                            Keterangan
                        </label>

                        <textarea class="form-control" id="description" name="description" rows="3" maxlength="500" placeholder="Masukkan keterangan kategori"></textarea>
                    </div>
                </div>
                {{-- Modal Footer --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"> Batal</button>

                    <button type="button" class="btn btn-primary" id="saveCategoryProduct">
                        <i class="ti ti-device-floppy me-1"></i>  Simpan
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            frontTable();
        });

        /**
         * DataTable
         */
        function frontTable() {
            $('#frontTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('datamaster/masterproducts/category-products/front_table') }}",

                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'category_code',
                        name: 'category_code'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        defaultContent: '-'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function (data) {
                            if (data === 'active') {
                                return `
                                    <span class="badge bg-success-subtle text-success">
                                        Aktif
                                    </span>
                                `;
                            }

                            return `
                                <span class="badge bg-danger-subtle text-danger">
                                    Tidak Aktif
                                </span>
                            `;
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

        /**
         * Clear Validation
         */
        function clearValidation() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
        }

        /**
         * Show Validation Errors
         */
        function showValidationErrors(messages) {
            clearValidation();

            $.each(messages, function (field, errors) {
                const input = $('#' + field);

                if (input.length) {
                    input.addClass('is-invalid');

                    input.after(
                        '<div class="invalid-feedback">' +
                        errors[0] +
                        '</div>'
                    );
                }
            });
        }

        /**
         * Add Category
         */
        $('#btnAddCategoryProduct').on('click', function () {
            clearValidation();

            $('#id').val('');
            $('#category_code').val('');
            $('#category_name').val('');
            $('#description').val('');

            $('#categoryProductModalLabel').text(
                'Tambah Kategori Produk'
            );

            $('#categoryProductModal').modal('show');
        });

        /**
         * Save / Update Category
         */
        $('#saveCategoryProduct').on('click', function () {
            clearValidation();

            const id = $('#id').val();

            const data = {
                _token: "{{ csrf_token() }}",
                category_code: $('#category_code').val(),
                category_name: $('#category_name').val(),
                description: $('#description').val()
            };

            if (id) {
                data.id = id;
            }

            $.ajax({
                url: id
                    ? "{{ url('datamaster/masterproducts/category-products/update') }}"
                    : "{{ url('datamaster/masterproducts/category-products/store') }}",

                type: 'POST',
                data: data,

                success: function (res) {
                    if (res.status === true) {
                        $('#categoryProductModal').modal('hide');

                        $('#frontTable')
                            .DataTable()
                            .ajax
                            .reload(null, false);

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } else {
                        showValidationErrors(res.message);
                    }
                },

                error: function (xhr) {
                    if (
                        xhr.status === 422 &&
                        xhr.responseJSON?.message
                    ) {
                        showValidationErrors(
                            xhr.responseJSON.message
                        );

                        return;
                    }

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: xhr.responseJSON?.message ||
                            'Terjadi kesalahan',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            });
        });

        /**
         * Edit Category
         */
        function editCategoryProduct(id) {
            $.ajax({
                url: "{{ url('datamaster/masterproducts/category-products/show') }}",

                type: 'POST',

                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },

                success: function (res) {
                    if (res.status === true) {
                        clearValidation();

                        $('#id').val(res.data.id);

                        $('#category_code').val(
                            res.data.category_code
                        );

                        $('#category_name').val(
                            res.data.category_name
                        );

                        $('#description').val(
                            res.data.description
                        );

                        $('#categoryProductModalLabel').text(
                            'Edit Kategori Produk'
                        );

                        $('#categoryProductModal').modal('show');
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                },

                error: function (xhr) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: xhr.responseJSON?.message ||
                            'Gagal mengambil data',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        }

        /**
         * Delete Category
         */
        function deleteCategoryProduct(id) {
            Swal.fire({
                title: 'Hapus kategori produk?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: "{{ url('datamaster/masterproducts/category-products/delete') }}",

                    type: 'POST',

                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },

                    success: function (res) {
                        $('#frontTable')
                            .DataTable()
                            .ajax
                            .reload(null, false);

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: res.status
                                ? 'success'
                                : 'error',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    },

                    error: function (xhr) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: xhr.responseJSON?.message ||
                                'Gagal menghapus kategori',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });
        }
    </script>
@endsection