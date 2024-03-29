@extends('adminlte::page')

@section('title', 'Antrian Poliklinik')

@section('content_header')
    <h1>Antrian Poliklinik</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Filter Data Antrian" theme="secondary" collapsible>
                <form action="{{ route('antrian.poli') }}" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <x-adminlte-input name="user" label="User" readonly value="{{ Auth::user()->name }}" />
                        </div>
                        <div class="col-md-3">
                            @php
                                $config = ['format' => 'YYYY-MM-DD'];
                            @endphp
                            <x-adminlte-input-date name="tanggal" label="Tanggal Antrian" :config="$config"
                                value="{{ \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d') }}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text bg-primary">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input-date>
                        </div>
                        <div class="col-md-3">
                            <x-adminlte-select2 name="poli" id="poli" label="Poliklinik">
                                <option value="00000">00000 - SEMUA POLIKLINIK</option>
                                @foreach ($polis as $item)
                                    <option value="{{ $item->kodesubspesialis }}">{{ $item->kodesubspesialis }} -
                                        {{ $item->namasubspesialis }}
                                    </option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>
                        <div class="col-md-3">
                            <x-adminlte-select2 name="dokter" label="Dokter">
                                <option value="00000">00000 - SEMUA DOKTER</option>
                                @foreach ($dokters as $item)
                                    <option value="{{ $item->kodedokter }}">{{ $item->kodedokter }} -
                                        {{ $item->namadokter }}
                                    </option>
                                @endforeach
                                </x-adminlte-select>
                        </div>
                    </div>
                    <x-adminlte-button type="submit" class="withLoad" theme="primary" label="Submit Antrian" />
                    <x-adminlte-button class="withLoad" theme="success" label="Tambah Antrian Offline" />
                </form>
            </x-adminlte-card>
            @if (isset($request->poli) && isset($request->dokter) && isset($request->tanggal))
                <x-adminlte-card title="Antrian Pelayanan Poliklinik ({{ $antrians->where('taskid', 3)->count() }} Orang)"
                    theme="primary" icon="fas fa-info-circle" collapsible>
                    @if ($errors->any())
                        <x-adminlte-alert title="Ops Terjadi Masalah !" theme="danger" dismissable>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-adminlte-alert>
                    @endif
                    @php
                        $heads = ['No', 'Kode', 'Tanggal', 'No RM / NIK', 'Jenis / Pasien', 'No Kartu / Rujukan', 'Poliklinik / Dokter', 'Status', 'Action'];
                        $config['order'] = ['7', 'asc'];
                    @endphp
                    <x-adminlte-datatable id="table1" class="nowrap" :heads="$heads" :config="$config" striped
                        bordered hoverable compressed>
                        @foreach ($antrians as $item)
                            <tr>
                                <td>{{ $item->angkaantrean }}</td>
                                <td>{{ $item->kodebooking }}<br>
                                    {{ $item->nomorantrean }}
                                </td>
                                <td>{{ $item->tanggalperiksa }}</td>
                                <td>
                                    {{ $item->norm }} <br>
                                    {{ $item->nik }}

                                </td>
                                <td>
                                    {{ $item->jenispasien }}
                                    @if ($item->pasienbaru == 1)
                                        <span class="badge bg-secondary">{{ $item->pasienbaru }}. Baru</span>
                                    @endif
                                    @if ($item->pasienbaru == 0)
                                        <span class="badge bg-secondary">{{ $item->pasienbaru }}. Lama</span>
                                    @endif
                                    @isset($item->pasien)
                                        <br>
                                        {{ $item->pasien->nama }}
                                    @endisset
                                </td>
                                <td>
                                    @isset($item->nomorkartu)
                                        {{ $item->nomorkartu }}
                                    @endisset
                                    @isset($item->nomorkartu)
                                        <br> {{ $item->nomorreferensi }}
                                    @endisset
                                </td>
                                <td>{{ $item->namapoli }}<br>{{ $item->namadokter }} <br>{{ $item->jampraktek }}
                                </td>
                                <td>
                                    @if ($item->taskid == 3)
                                        @if ($item->status_api == 0)
                                            <span class="badge bg-warning">{{ $item->taskid }}. Belum Pembayaran</span>
                                        @else
                                            <span class="badge bg-warning">{{ $item->taskid }}. Tunggu Poli</span>
                                        @endif
                                    @endif
                                    @if ($item->taskid == 4)
                                        <span class="badge bg-success">{{ $item->taskid }}. Periksa Poli</span>
                                    @endif
                                    @if ($item->taskid == 5)
                                        @if ($item->status_api == 1)
                                            <span class="badge bg-success">{{ $item->taskid }}. Tunggu Farmasi</span>
                                        @endif
                                        @if ($item->status_api == 2)
                                            <span class="badge bg-success">{{ $item->taskid }}. Selesai</span>
                                        @endif
                                    @endif
                                    @if ($item->taskid == 6)
                                        <span class="badge bg-success">{{ $item->taskid }}. Racik Obat</span>
                                    @endif
                                    @if ($item->taskid == 7)
                                        <span class="badge bg-success">{{ $item->taskid }}. Selesai</span>
                                    @endif
                                    @if ($item->taskid == 99)
                                        <span class="badge bg-danger">{{ $item->taskid }}. Batal</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->taskid == 3)
                                        {{-- panggil pertama --}}
                                        @if ($item->status_api == 1)
                                            <x-adminlte-button class="btn-xs" label="Panggil" theme="success"
                                                icon="fas fa-volume-down" data-toggle="tooltip"
                                                title="Panggil Antrian {{ $item->nomorantrean }}"
                                                onclick="window.location='{{ route('antrian.panggil_poli', $item->kodebooking) }}'" />
                                        @endif

                                        <x-adminlte-button class="btn-xs" theme="danger" icon="fas fa-times"
                                            data-toggle="tooltop" title="Batal Antrian {{ $item->nomorantrean }}"
                                            onclick="window.location='{{ route('antrian.batal_antrian', $item->kodebooking) }}'" />
                                    @endif
                                    {{-- panggil ulang --}}
                                    @if ($item->taskid == 4)
                                        <x-adminlte-button class="btn-xs" label="Panggil Ulang" theme="primary"
                                            icon="fas fa-volume-down" data-toggle="tooltip" title=""
                                            onclick="window.location='{{ route('antrian.panggil_poli', $item->kodebooking) }}'" />
                                        <x-adminlte-button class="btn-xs btnLayani" label="Layani" theme="success"
                                            icon="fas fa-hand-holding-medical" data-toggle="tooltop" title="Layani"
                                            data-id="{{ $item->id }}" />
                                        <x-adminlte-button class="btn-xs" theme="danger" icon="fas fa-times"
                                            data-toggle="tooltop" title="Batal Antrian {{ $item->nomorantrean }}"
                                            onclick="window.location='{{ route('antrian.batal_antrian', $item->kodebooking) }}'" />
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </x-adminlte-card>
                @if ($antrians->count() > 0)
                    <x-adminlte-modal id="modalPembayaran" title="Pembayaran Antrian Pasien" size="xl" theme="success"
                        icon="fas fa-user-plus" v-centered>
                        <form name="formLayanan" id="formLayanan" action="{{ route('antrian.pendaftaran') }}"
                            method="post">
                            @csrf
                            <input type="hidden" name="antrianid" id="antrianid" value="">
                            <dl class="row">
                                <dt class="col-sm-3">Kode Booking</dt>
                                <dd class="col-sm-8">: <span id="kodebooking"></span></dd>
                                <dt class="col-sm-3">Antrian</dt>
                                <dd class="col-sm-8">: <span id="angkaantrean"></span> / <span
                                        id="nomorantrean"></span>
                                </dd>
                                <dt class="col-sm-3 ">Tanggal Perikasa</dt>
                                <dd class="col-sm-8">: <span id="tanggalperiksa"></span></dd>
                                <dt class="col-sm-3">Administrator</dt>
                                <dd class="col-sm-8">: {{ Auth::user()->name }}</dd>
                            </dl>
                            <x-adminlte-card theme="primary" title="Informasi Kunjungan Berobat">
                                <div class="row">
                                    <div class="col-md-5">
                                        <dl class="row">
                                            <dt class="col-sm-4">No RM</dt>
                                            <dd class="col-sm-8">: <span id="norm"></span></dd>
                                            <dt class="col-sm-4">NIK</dt>
                                            <dd class="col-sm-8">: <span id="nik"></span></dd>
                                            <dt class="col-sm-4">No BPJS</dt>
                                            <dd class="col-sm-8">: <span id="nomorkartu"></span></dd>
                                            <dt class="col-sm-4">Nama</dt>
                                            <dd class="col-sm-8">: <span id="nama"></span></dd>
                                            <dt class="col-sm-4">No HP</dt>
                                            <dd class="col-sm-8">: <span id="nohp"></span></dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-7">
                                        <dl class="row">
                                            <dt class="col-sm-4">No Rujukan</dt>
                                            <dd class="col-sm-8">: <span id="nomorreferensi"></span></dd>
                                            <dt class="col-sm-4">Jenis Pasien</dt>
                                            <dd class="col-sm-8">: <span id="jenispasien"></span></dd>
                                            <dt class="col-sm-4">Poliklinik</dt>
                                            <dd class="col-sm-8">: <span id="namapoli"></span></dd>
                                            <dt class="col-sm-4">Dokter</dt>
                                            <dd class="col-sm-8">: <span id="namadokter"></span></dd>
                                            <dt class="col-sm-4">Jadwal</dt>
                                            <dd class="col-sm-8">: <span id="jampraktek"></span></dd>
                                            <dt class="col-sm-4">Jenis Kunjungan</dt>
                                            <dd class="col-sm-8">: <span id="jeniskunjungan"></span></dd>
                                        </dl>
                                    </div>
                                </div>
                            </x-adminlte-card>
                            <x-slot name="footerSlot">
                                <x-adminlte-button class="mr-auto" label="Lanjut Farmasi" theme="warning"
                                    icon="fas fa-prescription-bottle-alt"
                                    onclick=" window.location='{{ route('antrian.lanjut_farmasi', $item->kodebooking) }}'" />
                                <x-adminlte-button label="Selesai" theme="success" icon="fas fa-check"
                                    onclick="window.location='{{ route('antrian.selesai', $item->kodebooking) }}'" />
                                <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal" />
                            </x-slot>
                        </form>
                    </x-adminlte-modal>
                @endif
            @endif
        </div>
    </div>

@stop

@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.TempusDominusBs4', true)

@section('js')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.btnLayani').click(function() {
                var antrianid = $(this).data('id');
                $.LoadingOverlay("show");
                $.get("{{ route('antrian.index') }}" + '/' + antrianid + '/edit', function(data) {
                    // console.log(data);
                    $('#kodebooking').html(data.kodebooking);
                    $('#angkaantrean').html(data.angkaantrean);
                    $('#nomorantrean').html(data.nomorantrean);
                    $('#tanggalperiksa').html(data.tanggalperiksa);

                    $('#norm').html(data.norm);
                    $('#nik').html(data.nik);
                    $('#nomorkartu').html(data.nomorkartu);
                    $('#nama').html(data.nama);
                    $('#nohp').html(data.nohp);

                    $('#nomorreferensi').html(data.nomorreferensi);
                    $('#jenispasien').html(data.jenispasien);
                    $('#namapoli').html(data.namapoli);
                    $('#namadokter').html(data.namadokter);
                    $('#jampraktek').html(data.jampraktek);
                    $('#jeniskunjungan').html(data.jeniskunjungan);

                    $('#user').html(data.user);
                    $('#antrianid').val(antrianid);
                    $('#namapoli').val(data.namapoli);
                    $('#namadokter').val(data.namadokter);
                    $('#kodepoli').val(data.kodepoli);
                    $('#kodedokter').val(data.kodedokter);
                    $('#jampraktek').val(data.jampraktek);
                    // $('#kodepoli').val(data.kodepoli).trigger('change');
                    $('#modalPembayaran').modal('show');
                    $.LoadingOverlay("hide", true);
                })
            });
        });
    </script>
@endsection
