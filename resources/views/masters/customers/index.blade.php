@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h5 class="card-title fw-semibold mb-1">Daftar Customer</h5>
                            <p class="card-subtitle mb-0">Kelola data customer</p>
                        </div>
                        <div
                            class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 flex-shrink-0">
                            <button type="button" class="btn btn-primary d-flex align-items-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#addCustomerModal" id="btnTambahCustomer">
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
                                    <th>Company</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>NIK</th>
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

        <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-semibold" id="addCustomerModalLabel">Tambah customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <input type="text" id="id" hidden>
                                <label for="name" class="form-label">Company</label>
                                <select class="form-select" id="company_id" name="company_id">
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Tipe Customer</label>
                                <select class="form-select" id="customer_type" name="customer_type">
                                    <option value="Individu" selected>Individu</option>
                                    <option value="Perusahaan">Perusahaan</option>
                                    <option value="Asuransi">Asuransi</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Kode Customer</label>
                                <input type="text" class="form-control" id="code" name="code"
                                    value="{{ old('code') }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Nama Customer</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Tanggal lahir</label>
                                <input type="date" class="form-control" id="brith_date" name="brith_date"
                                    value="{{ old('brith_date') }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik"
                                    value="{{ old('nik') }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">No Telp</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ old('phone') }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Negara</label>
                                <input type="text" class="form-control" id="country" name="country"
                                    value="{{ old('country') }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Provinsi</label>
                                <input type="text" class="form-control" id="province" name="province"
                                    value="{{ old('province') }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Kabupaten/Kota</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    value="{{ old('city') }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Kecamatan</label>
                                <input type="text" class="form-control" id="district" name="district"
                                    value="{{ old('district') }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Kode Pos</label>
                                <input type="number" class="form-control" id="postal_code" name="postal_code"
                                    value="{{ old('postal_code') }}">
                            </div>
                            <div class="mb-3 col-md-6">
                                <input type="text" id="id" hidden>
                                <label for="name" class="form-label">Is Active</label>
                                <select class="form-select" id="is_active" name="is_active">
                                    <option value="1">Active</option>
                                    <option value="0">Non active</option>
                                </select>
                            </div>
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
        $('#company_id').select2({
            placeholder: 'Pilih Company',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#addCustomerModal'),

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

        function frontTable() {
            if ($.fn.DataTable.isDataTable('#frontTable')) {
                $('#frontTable').DataTable().destroy();
            }
            $("#frontTable").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('masters/customers/front_table') }}",
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'company_name',
                    name: 'company_name'
                }, {
                    data: 'customer_type',
                    name: 'customer_type'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'nik',
                    name: 'nik'
                }, {
                    data: 'status',
                    name: 'status'
                }, {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }]
            })
        }
        $("#btnTambahCustomer").on('click', function() {

            $("#id").val('');
            $('#company_id').val(null).trigger('change');
            $('#customer_type').val('Individu');
            $("#code").val('');
            $("#name").val('');
            $("#brith_date").val('');
            $("#nik").val('');
            $("#phone").val('');
            $("#email").val('');
            $("#country").val('');
            $("#province").val('');
            $("#city").val('');
            $("#district").val('');
            $("#postal_code").val('');
            $("#is_active").val('1');

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $("#addCustomerModalLabel").text('Tambah Customer');
        });
        $("#simpan").on('click', function() {

            const id = $("#id").val();

            let data = {
                '_token': "{{ csrf_token() }}",
                'customer_type': $("#customer_type").val(),
                'company_id': $("#company_id").val(),
                'code': $("#code").val(),
                'name': $("#name").val(),
                'brith_date': $("#brith_date").val(),
                'nik':$("#nik").val(),
                'phone': $("#phone").val(),
                'email': $("#email").val(),
                'country': $("#country").val(),
                'province': $("#province").val(),
                'city': $("#city").val(),
                'district': $("#district").val(),
                'postal_code': $("#postal_code").val(),
                'is_active': $("#is_active").val()
            };

            if (id) {
                data.id = id;
            }

            const url = id ?
                "{{ url('masters/customers/update') }}" :
                "{{ url('masters/customers/store') }}";

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
                            timerProgressBar: true
                        });

                        $('#frontTable').DataTable().ajax.reload(null, false);

                        const modal = bootstrap.Modal.getInstance(
                            document.getElementById('addCustomerModal')
                        );

                        if (modal) {
                            modal.hide();
                        }

                    } else {

                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();
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
                    console.log(xhr);
                    console.log(xhr.responseText);
                    let message = 'Terjadi kesalahan pada server';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            });
        });

        function editCustomer(id) {
            $.ajax({
                url: "{{ url('masters/customers/show') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },

                success: function(res) {
                    if (res.status === true) {
                        const data = res.data;
                        $("#id").val(data.id);
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
                        $("#customer_type").val(data.customer_type).trigger('change')
                        $("#code").val(data.code);
                        $("#name").val(data.name);
                        $("#brith_date").val(data.brith_date);
                        $("#nik").val(data.nik);
                        $("#phone").val(data.phone);
                        $("#email").val(data.email);
                        $("#country").val(data.country);
                        $("#province").val(data.province);
                        $("#city").val(data.city);
                        $("#district").val(data.district);
                        $("#postal_code").val(data.postal_code);
                        $("#is_active").val(data.is_active);

                        $("#addCustomerModalLabel").text('Edit customer');

                        const modal = new bootstrap.Modal(
                            document.getElementById('addCustomerModal')
                        );

                        modal.show();

                    } else {

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: res.message || 'Data customer tidak ditemukan',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                },

                error: function(xhr) {

                    let message = 'Gagal mengambil data customer';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        }

        function deleteCustomer(id) {
            Swal.fire({
                title: 'Nonaktifkan customer?',
                text: 'Customer tidak dapat digunakan sampai diaktifkan kembali.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, nonaktifkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: "{{ url('masters/customers/delete') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: res.message,
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            $('#frontTable').DataTable().ajax.reload(null, false);
                            return;
                        }

                        Swal.fire('Gagal', res.message || 'customer tidak ditemukan.', 'error');
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Gagal',
                            xhr.responseJSON?.message || 'Terjadi kesalahan pada server.',
                            'error'
                        );
                    }
                });
            });
        }
    </script>
@endsection
