@extends('layouts.master')

@section('title', 'Kelola Pelabuhan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">🚢 Kelola Pelabuhan</h2>
        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="openAddPortModal()">
            <i class="fa-solid fa-plus me-1"></i> Tambah Pelabuhan
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow border-0 rounded-4 p-4">
        <h5 class="fw-bold mb-3">Daftar Pelabuhan (Dataset WPI)</h5>
        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-hover align-middle">
                <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelabuhan</th>
                        <th>Kode</th>
                        <th>Negara</th>
                        <th>WPI No.</th>
                        <th>Wilayah</th>
                        <th>Tunda (Jam)</th>
                        <th>Koordinat</th>
                        <th>Kepadatan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ports as $port)
                        <tr>
                            <td>{{ $port->id }}</td>
                            <td><b>{{ $port->name }}</b></td>
                            <td><code>{{ $port->code }}</code></td>
                            <td>{{ $port->country_code }}</td>
                            <td>{{ $port->wpi_number }}</td>
                            <td>{{ $port->region }}</td>
                            <td>{{ $port->delay_hours }}j</td>
                            <td>{{ number_format($port->latitude, 4) }}, {{ number_format($port->longitude, 4) }}</td>
                            <td>
                                <span class="badge {{ $port->congestion_status === 'High' ? 'bg-danger' : ($port->congestion_status === 'Medium' ? 'bg-warning text-dark' : 'bg-success') }}">
                                    {{ $port->congestion_status }}
                                </span>
                            </td>
                            <td class="text-center" style="white-space: nowrap;">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1"
                                        onclick="openEditPortModal({{ $port->id }}, '{{ addslashes($port->name) }}', '{{ $port->code }}', '{{ $port->country_code }}', {{ $port->latitude }}, {{ $port->longitude }}, '{{ $port->congestion_status }}', '{{ $port->wpi_number }}', '{{ addslashes($port->region) }}', {{ $port->delay_hours }})">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                
                                <form action="{{ route('admin.ports.destroy', $port->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelabuhan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Port -->
<div class="modal fade" id="portModal" tabindex="-1" aria-labelledby="portModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="portModalLabel">🚢 Pelabuhan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="portForm" method="POST" action="">
                @csrf
                <div id="methodContainer"></div>
                <div class="modal-body py-3">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="port_name" class="form-label small fw-semibold text-muted">Nama Pelabuhan</label>
                            <input type="text" class="form-control rounded-3" id="port_name" name="name" required placeholder="Contoh: Port of Hamburg">
                        </div>
                        <div class="col-md-4">
                            <label for="port_code" class="form-label small fw-semibold text-muted">Kode Pelabuhan</label>
                            <input type="text" class="form-control rounded-3" id="port_code" name="code" placeholder="Contoh: DEHAM">
                        </div>
                        <div class="col-md-4">
                            <label for="port_country_code" class="form-label small fw-semibold text-muted">Kode Negara (2 Huruf)</label>
                            <input type="text" class="form-control rounded-3" id="port_country_code" name="country_code" required maxlength="5" placeholder="Contoh: DE">
                        </div>
                        <div class="col-md-4">
                            <label for="port_region" class="form-label small fw-semibold text-muted">Wilayah (Region)</label>
                            <input type="text" class="form-control rounded-3" id="port_region" name="region" placeholder="Contoh: Europe">
                        </div>
                        <div class="col-md-4">
                            <label for="port_wpi_number" class="form-label small fw-semibold text-muted">Nomor WPI</label>
                            <input type="text" class="form-control rounded-3" id="port_wpi_number" name="wpi_number" placeholder="Contoh: WPI-30910">
                        </div>
                        <div class="col-md-4">
                            <label for="port_latitude" class="form-label small fw-semibold text-muted">Latitude</label>
                            <input type="number" step="any" class="form-control rounded-3" id="port_latitude" name="latitude" required placeholder="Contoh: 53.5458">
                        </div>
                        <div class="col-md-4">
                            <label for="port_longitude" class="form-label small fw-semibold text-muted">Longitude</label>
                            <input type="number" step="any" class="form-control rounded-3" id="port_longitude" name="longitude" required placeholder="Contoh: 9.9658">
                        </div>
                        <div class="col-md-4">
                            <label for="port_delay_hours" class="form-label small fw-semibold text-muted">Waktu Tunda (Jam)</label>
                            <input type="number" class="form-control rounded-3" id="port_delay_hours" name="delay_hours" required min="0" placeholder="0">
                        </div>
                        <div class="col-md-12">
                            <label for="port_congestion_status" class="form-label small fw-semibold text-muted">Status Kepadatan (Congestion)</label>
                            <select class="form-select rounded-3" id="port_congestion_status" name="congestion_status" required>
                                <option value="Low">Low / Rendah</option>
                                <option value="Medium">Medium / Sedang</option>
                                <option value="High">High / Tinggi</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const portModalEl = document.getElementById('portModal');
    const portModal = new bootstrap.Modal(portModalEl);
    const form = document.getElementById('portForm');
    const methodContainer = document.getElementById('methodContainer');
    const modalTitle = document.getElementById('portModalLabel');

    function openAddPortModal() {
        form.reset();
        form.action = "{{ route('admin.ports.store') }}";
        methodContainer.innerHTML = '';
        modalTitle.textContent = '➕ Tambah Pelabuhan';
        
        portModal.show();
    }

    function openEditPortModal(id, name, code, country_code, latitude, longitude, status, wpi_number, region, delay_hours) {
        form.reset();
        form.action = `/admin/ports/${id}`;
        methodContainer.innerHTML = '@method("PUT")';
        modalTitle.textContent = '✏️ Edit Pelabuhan';

        document.getElementById('port_name').value = name;
        document.getElementById('port_code').value = code || '';
        document.getElementById('port_country_code').value = country_code;
        document.getElementById('port_region').value = region || '';
        document.getElementById('port_wpi_number').value = wpi_number || '';
        document.getElementById('port_latitude').value = latitude;
        document.getElementById('port_longitude').value = longitude;
        document.getElementById('port_delay_hours').value = delay_hours;
        document.getElementById('port_congestion_status').value = status;

        portModal.show();
    }
</script>
@endsection
