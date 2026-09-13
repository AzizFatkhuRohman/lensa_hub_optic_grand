@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="col-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h5 class="card-title fw-semibold mb-1">Daftar Menu</h5>
                            <p class="card-subtitle mb-0">Kelola menu navigasi aplikasi</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 flex-shrink-0">
                            <span class="badge bg-light-primary text-primary">{{ $menus->count() }} menu</span>
                            <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                data-bs-target="#addMenuModal">
                                <i class="ti ti-plus"></i>
                                <span>Tambah Menu</span>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Level</th>
                                    <th>Parent</th>
                                    <th>URL</th>
                                    <th>Icon</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($menus as $menu)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0 fw-semibold">{{ $menu->name }}</h6>
                                            @if ($menu->description)
                                                <small class="text-muted">{{ $menu->description }}</small>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-light-secondary text-secondary">Level {{ $menu->level }}</span></td>
                                        <td>{{ $menus->firstWhere('id', $menu->parent_id)?->name ?? '-' }}</td>
                                        <td>{{ $menu->url ?: '-' }}</td>
                                        <td><i class="{{ $menu->icon ?: 'ti ti-circle' }}"></i></td>
                                        <td class="text-end">
                                            <div class="d-inline-flex align-items-center gap-2">
                                                <button type="button" class="btn btn-sm btn-light-primary edit-menu-btn"
                                                    title="Edit menu" data-bs-toggle="modal" data-bs-target="#editMenuModal"
                                                    data-id="{{ $menu->id }}" data-level="{{ $menu->level }}"
                                                    data-parent-id="{{ $menu->parent_id }}" data-name="{{ $menu->name }}"
                                                    data-icon="{{ $menu->icon }}" data-url="{{ $menu->url }}"
                                                    data-description="{{ $menu->description }}">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <form method="POST" action="{{ route('settings.menus.destroy', $menu) }}" class="delete-menu-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger" title="Hapus menu">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Belum ada menu.</td>
                                    </tr>
                                @endforelse
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
                    <form method="POST" action="{{ route('settings.menus.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="level" class="form-label">Level</label>
                                <select class="form-select" id="level" name="level" required>
                                    <option value="1" @selected(old('level', '1') == '1')>Level 1</option>
                                    <option value="2" @selected(old('level') == '2')>Level 2</option>
                                    <option value="3" @selected(old('level') == '3')>Level 3</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="parent_id" class="form-label">Parent</label>
                                <select class="form-select" id="parent_id" name="parent_id">
                                    <option value="">Tanpa parent</option>
                                    @foreach ($parentMenus as $parent)
                                        <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>
                                            Level {{ $parent->level }} - {{ $parent->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Menu</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="icon" class="form-label">Icon</label>
                                <select class="form-select" id="icon" name="icon">
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
                            <div class="mb-3">
                                <label for="url" class="form-label">URL</label>
                                <input type="text" class="form-control" id="url" name="url" value="{{ old('url') }}" placeholder="settings/example">
                            </div>
                            <div class="mb-0">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Menu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-semibold" id="editMenuModalLabel">Edit Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <form method="POST" id="editMenuForm">
                        @csrf
                        @method('PUT')
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
                                    @foreach ($parentMenus as $parent)
                                        <option value="{{ $parent->id }}">Level {{ $parent->level }} - {{ $parent->name }}</option>
                                    @endforeach
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
                                <input type="text" class="form-control" id="editUrl" name="url" placeholder="settings/example">
                            </div>
                            <div class="mb-0">
                                <label for="editDescription" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('addMenuModal'));
                modal.show();
            });
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-menu-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const form = document.getElementById('editMenuForm');
                    form.action = '{{ url('settings/menus') }}/' + this.dataset.id;
                    document.getElementById('editLevel').value = this.dataset.level;
                    document.getElementById('editParentId').value = this.dataset.parentId || '';
                    document.getElementById('editName').value = this.dataset.name || '';
                    document.getElementById('editIcon').value = this.dataset.icon || '';
                    document.getElementById('editUrl').value = this.dataset.url || '';
                    document.getElementById('editDescription').value = this.dataset.description || '';
                });
            });

            document.querySelectorAll('.delete-menu-form').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: 'Hapus menu ini?',
                        text: 'Menu yang dihapus tidak dapat dikembalikan.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-danger px-4 ms-2',
                            cancelButton: 'btn btn-light px-4'
                        }
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    timer: 2200,
                    showConfirmButton: false
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak dapat menghapus',
                    text: @json(session('error')),
                    confirmButtonText: 'Mengerti'
                });
            @endif
        });
    </script>
@endsection