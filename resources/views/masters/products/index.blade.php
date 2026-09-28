@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h5 class="card-title fw-semibold mb-1">Data Produk</h5>
                            <p class="card-subtitle mb-0">Kelola data produk</p>
                        </div>
                        <div
                            class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 flex-shrink-0">
                            <button type="button" class="btn btn-primary d-flex align-items-center gap-2"
                                id="btnAddProduct">
                                <i class="ti ti-plus"></i>
                                <span>Tambah Produk</span>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0" id="frontTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Brand</th>
                                    <th>Tipe</th>
                                    <th>Warna</th>
                                    <th>Satuan</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Min. Stok</th>
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
    </div>


    {{-- =============================================================
    MODAL PRODUCT
============================================================= --}}
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-centered">

            <div class="modal-content">

                {{-- Modal Header --}}
                <div class="modal-header">

                    <h5 class="modal-title fw-semibold" id="productModalLabel">
                        Tambah Produk
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup">
                    </button>

                </div>


                {{-- Modal Body --}}
                <div class="modal-body">

                    <input type="hidden" id="id" name="id">


                    <div class="row">

                        {{-- =================================================
                        PRODUCT CODE
                    ================================================== --}}
                        <div class="col-md-6 mb-3">

                            <label for="product_code" class="form-label">
                                Kode Produk
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" class="form-control" required id="product_code" name="product_code"
                                maxlength="50" placeholder="Contoh: PRD-001">

                            <div class="invalid-feedback" id="error-product_code">
                            </div>

                        </div>


                        {{-- =================================================
                        PRODUCT NAME
                    ================================================== --}}
                        <div class="col-md-6 mb-3">

                            <label for="product_name" class="form-label">
                                Nama Produk
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" class="form-control" required id="product_name" name="product_name"
                                maxlength="100" placeholder="Masukkan nama produk">

                            <div class="invalid-feedback" id="error-product_name">
                            </div>

                        </div>


                        {{-- =================================================
                        CATEGORY
                    ================================================== --}}
                        <div class="col-md-6 mb-3">

                            <label for="category_id" class="form-label">
                                Kategori
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" required id="category_id" name="category_id">

                                <option value="">
                                    -- Pilih Kategori --
                                </option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach

                            </select>

                            <div class="invalid-feedback" id="error-category_id">
                            </div>

                        </div>


                        {{-- =================================================
                        BRAND
                    ================================================== --}}
                        <div class="col-md-6 mb-3">

                            <label for="brand_id" class="form-label">
                                Brand
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" required id="brand_id" name="brand_id">

                                <option value="">
                                    -- Pilih Brand --
                                </option>

                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">
                                        {{ $brand->brand_name }}
                                    </option>
                                @endforeach

                            </select>

                            <div class="invalid-feedback" id="error-brand_id">
                            </div>

                        </div>


                        {{-- =================================================
                        TYPE
                    ================================================== --}}
                        <div class="col-md-6 mb-3">

                            <label for="type_id" class="form-label">
                                Tipe
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" required id="type_id" name="type_id">

                                <option value="">
                                    -- Pilih Tipe --
                                </option>

                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->type_name }}
                                    </option>
                                @endforeach

                            </select>

                            <div class="invalid-feedback" id="error-type_id">
                            </div>

                        </div>


                        {{-- =================================================
                        COLOR
                    ================================================== --}}
                        <div class="col-md-6 mb-3">

                            <label for="color_id" class="form-label">
                                Warna
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" required id="color_id" name="color_id">

                                <option value="">
                                    -- Pilih Warna --
                                </option>

                                @foreach ($colors as $color)
                                    <option value="{{ $color->id }}">
                                        {{ $color->color_name }}
                                    </option>
                                @endforeach

                            </select>

                            <div class="invalid-feedback" id="error-color_id">
                            </div>

                        </div>


                        {{-- =================================================
                        UNIT
                    ================================================== --}}
                        <div class="col-md-6 mb-3">

                            <label for="unit_id" class="form-label">
                                Satuan
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" required id="unit_id" name="unit_id">

                                <option value="">
                                    -- Pilih Satuan --
                                </option>

                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">
                                        {{ $unit->unit_name }}
                                    </option>
                                @endforeach

                            </select>

                            <div class="invalid-feedback" id="error-unit_id">
                            </div>

                        </div>


                        {{-- =================================================
                        PURCHASE PRICE
                    ================================================== --}}
                        <div class="col-md-6 mb-3">

                            <label for="purchase_price" class="form-label">
                                Harga Beli
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number" class="form-control" required id="purchase_price"
                                name="purchase_price" min="0" step="0.01" placeholder="0">

                            <div class="invalid-feedback" id="error-purchase_price">
                            </div>

                        </div>


                        {{-- =================================================
                        SELLING PRICE
                    ================================================== --}}
                        <div class="col-md-6 mb-3">

                            <label for="selling_price" class="form-label">
                                Harga Jual
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number" class="form-control" required id="selling_price" name="selling_price"
                                min="0" step="0.01" placeholder="0">

                            <div class="invalid-feedback" id="error-selling_price">
                            </div>

                        </div>


                        {{-- =================================================
                        MINIMUM STOCK
                    ================================================== --}}
                        <div class="col-md-6 mb-3">

                            <label for="minimum_stock" class="form-label">
                                Minimum Stok
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number" class="form-control" required id="minimum_stock" name="minimum_stock"
                                min="0" step="1" placeholder="0">

                            <div class="invalid-feedback" id="error-minimum_stock">
                            </div>

                        </div>


                        {{-- =================================================
                        STATUS
                    ================================================== --}}
                        <div class="col-md-6 mb-3">

                            <label for="status" class="form-label">
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <select class="form-select" required id="status" name="status">

                                <option value="active">
                                    Aktif
                                </option>

                                <option value="inactive">
                                    Tidak Aktif
                                </option>

                            </select>

                            <div class="invalid-feedback" id="error-status">
                            </div>

                        </div>

                    </div>

                </div>


                {{-- =========================================================
                MODAL FOOTER
            ========================================================== --}}
                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="button" class="btn btn-primary" id="btnSaveProduct">

                        <i class="ti ti-device-floppy me-1"></i>

                        Simpan

                    </button>

                </div>

            </div>

        </div>

    </div>
    <script>
        let productTable;


        /**
         * ============================================================
         * DOCUMENT READY
         * ============================================================
         */
        $(document).ready(function() {

            frontTable();


            /**
             * Tambah Product
             */
            $('#btnAddProduct').on('click', function() {

                clearForm();

                $('#productModalLabel').text('Tambah Produk');

                showProductModal();

            });


            /**
             * Simpan Product
             */
            $('#btnSaveProduct').on('click', function() {

                saveProduct();

            });

        });


        /**
         * ============================================================
         * DATATABLE
         * ============================================================
         */
        function frontTable() {

            productTable = $('#frontTable').DataTable({

                processing: true,

                serverSide: true,

                ajax: "{{ url('masters/products/product/front_table') }}",

                columns: [

                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'product_code',
                        name: 'product_code'
                    },

                    {
                        data: 'product_name',
                        name: 'product_name'
                    },

                    {
                        data: 'category_name',
                        name: 'category.category_name'
                    },

                    {
                        data: 'brand_name',
                        name: 'brand.brand_name'
                    },

                    {
                        data: 'type_name',
                        name: 'type.type_name'
                    },

                    {
                        data: 'color_name',
                        name: 'color.color_name'
                    },

                    {
                        data: 'unit_name',
                        name: 'unit.unit_name'
                    },

                    {
                        data: 'purchase_price',
                        name: 'purchase_price'
                    },

                    {
                        data: 'selling_price',
                        name: 'selling_price'
                    },

                    {
                        data: 'minimum_stock',
                        name: 'minimum_stock'
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


        /**
         * ============================================================
         * CLEAR FORM
         * ============================================================
         */
        function clearForm() {

            $('#id').val('');

            $('#product_code').val('');

            $('#product_name').val('');

            $('#category_id').val('');

            $('#brand_id').val('');

            $('#type_id').val('');

            $('#color_id').val('');

            $('#unit_id').val('');

            $('#purchase_price').val('');

            $('#selling_price').val('');

            $('#minimum_stock').val('');

            $('#status').val('active');

            clearValidation();

        }


        function showProductModal() {

            bootstrap.Modal.getOrCreateInstance(
                document.getElementById('productModal')
            ).show();

        }


        function hideProductModal() {

            bootstrap.Modal.getOrCreateInstance(
                document.getElementById('productModal')
            ).hide();

        }


        /**
         * ============================================================
         * CLEAR VALIDATION
         * ============================================================
         */
        function clearValidation() {

            $('.form-control, .form-select, textarea')
                .removeClass('is-invalid');

            $('.invalid-feedback')
                .html('');

        }


        /**
         * ============================================================
         * SHOW VALIDATION ERROR
         * ============================================================
         */
        function showValidationErrors(errors) {

            clearValidation();

            $.each(errors, function(field, messages) {

                const input = $('#' + field);

                input.addClass('is-invalid');

                $('#error-' + field).html(
                    messages[0]
                );

            });

        }


        /**
         * ============================================================
         * SAVE PRODUCT
         * ============================================================
         */
        function saveProduct() {

            clearValidation();

            const id = $('#id').val();

            let url = '';

            if (id) {

                url = "{{ url('masters/products/product/update') }}";

            } else {

                url = "{{ url('masters/products/product/store') }}";

            }


            const data = {

                _token: "{{ csrf_token() }}",

                id: id,

                product_code: $('#product_code').val(),

                product_name: $('#product_name').val(),

                category_id: $('#category_id').val(),

                brand_id: $('#brand_id').val(),

                type_id: $('#type_id').val(),

                color_id: $('#color_id').val(),

                unit_id: $('#unit_id').val(),

                purchase_price: $('#purchase_price').val(),

                selling_price: $('#selling_price').val(),

                minimum_stock: $('#minimum_stock').val(),

                status: $('#status').val()

            };


            $.ajax({

                url: url,

                type: 'POST',

                data: data,

                beforeSend: function() {

                    $('#btnSaveProduct')
                        .prop('disabled', true)
                        .html(
                            '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...'
                        );

                },

                success: function(response) {

                    if (response.status) {

                        hideProductModal();

                        productTable.ajax.reload(null, false);

                        Swal.fire({

                            icon: 'success',

                            title: 'Berhasil',

                            text: response.message,

                            timer: 1800,

                            showConfirmButton: false

                        });

                    } else {

                        Swal.fire({

                            icon: 'error',

                            title: 'Gagal',

                            text: response.message

                        });

                    }

                },

                error: function(xhr) {

                    if (xhr.status === 422) {

                        showValidationErrors(
                            xhr.responseJSON.errors
                        );

                        return;

                    }

                    Swal.fire({

                        icon: 'error',

                        title: 'Terjadi Kesalahan',

                        text: 'Data produk gagal disimpan.'

                    });

                },

                complete: function() {

                    $('#btnSaveProduct')
                        .prop('disabled', false)
                        .html(
                            '<i class="ti ti-device-floppy me-1"></i> Simpan'
                        );

                }

            });

        }


        /**
         * ============================================================
         * EDIT PRODUCT
         * ============================================================
         */
        function editProduct(id) {

            clearForm();

            showProductModal();

            $.ajax({

                url: "{{ url('masters/products/product/show') }}",

                type: 'POST',

                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },

                success: function(response) {

                    if (!response.status) {

                        hideProductModal();

                        Swal.fire({

                            icon: 'error',

                            title: 'Gagal',

                            text: response.message

                        });

                        return;

                    }


                    const product = response.data;


                    $('#id').val(product.id);

                    $('#product_code').val(
                        product.product_code
                    );

                    $('#product_name').val(
                        product.product_name
                    );

                    $('#category_id').val(
                        product.category_id
                    );

                    $('#brand_id').val(
                        product.brand_id
                    );

                    $('#type_id').val(
                        product.type_id
                    );

                    $('#color_id').val(
                        product.color_id
                    );

                    $('#unit_id').val(
                        product.unit_id
                    );

                    $('#purchase_price').val(
                        product.purchase_price
                    );

                    $('#selling_price').val(
                        product.selling_price
                    );

                    $('#minimum_stock').val(
                        product.minimum_stock
                    );

                    $('#status').val(
                        product.status
                    );


                    $('#productModalLabel')
                        .text('Edit Produk');

                },

                error: function() {
                    hideProductModal();

                    Swal.fire({

                        icon: 'error',

                        title: 'Gagal',

                        text: 'Data produk tidak dapat diambil.'

                    });

                }

            });

        }


        /**
         * ============================================================
         * DELETE PRODUCT
         * ============================================================
         */
        function deleteProduct(id) {

            Swal.fire({

                title: 'Hapus Produk?',

                text: 'Data produk yang dihapus tidak dapat dikembalikan.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Ya, Hapus',

                cancelButtonText: 'Batal',

                reverseButtons: true

            }).then((result) => {

                if (!result.isConfirmed) {
                    return;
                }


                $.ajax({

                    url: "{{ url('masters/products/product/delete') }}",

                    type: 'POST',

                    data: {

                        _token: "{{ csrf_token() }}",

                        id: id

                    },

                    success: function(response) {

                        if (response.status) {

                            productTable.ajax.reload(
                                null,
                                false
                            );

                            Swal.fire({

                                icon: 'success',

                                title: 'Berhasil',

                                text: response.message,

                                timer: 1800,

                                showConfirmButton: false

                            });

                        } else {

                            Swal.fire({

                                icon: 'error',

                                title: 'Gagal',

                                text: response.message

                            });

                        }

                    },

                    error: function() {

                        Swal.fire({

                            icon: 'error',

                            title: 'Gagal',

                            text: 'Data produk gagal dihapus.'

                        });

                    }

                });

            });

        }
    </script>
@endsection
