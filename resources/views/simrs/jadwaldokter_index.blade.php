@extends('adminlte::page')

@section('title', 'Referensi Jadwal Dokter')

@section('content_header')
    <h1>Referensi Jadwal Dokter</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Data Informasi Jadwal Dokter" theme="info" icon="fas fa-info-circle" collapsible
                maximizable>
                @php
                    $heads = ['Nama Poliklinik', 'Kode Poli', 'Dokter', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                @endphp
                <x-adminlte-datatable id="table1" class="nowrap" :heads="$heads" striped bordered hoverable
                    compressed>
                    @foreach ($jadwals->groupby('namadokter') as $item)
                        <tr>
                            <td>{{ $item->first()->namasubspesialis }}</td>
                            <td>{{ $item->first()->kodesubspesialis }}</td>
                            <td>{{ $item->first()->namadokter }}</td>
                            @for ($i = 1; $i <= 6; $i++)
                                <td>
                                    @foreach ($item as $jadwal)
                                        @if ($jadwal->hari == $i)
                                            @if ($jadwal->libur == 1)
                                                <x-adminlte-button label="{{ $jadwal->jadwal }}"
                                                    class="btn-xs mb-1 btnJadwal" theme="danger" data-toggle="tooltip"
                                                    title="Jadwal Dokter" data-id="{{ $jadwal->id }}" />
                                            @else
                                                <x-adminlte-button label="{{ $jadwal->jadwal }}"
                                                    class="btn-xs mb-1 btnJadwal" theme="warning" data-toggle="tooltip"
                                                    title="Jadwal Dokter" data-id="{{ $jadwal->id }}" />
                                            @endif
                                        @endif
                                    @endforeach
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
                {{-- <a href="{{ route('antrian.ref.get_poli_bpjs') }}" class="btn btn-success">Refresh</a> --}}
                <x-adminlte-button label="Tambah Jadwal" theme="success" icon="fas fa-calendar-plus"
                data-toggle="modal" data-target="#modalAddJadwal" />
                <x-adminlte-button label="Tambah Jadwal (HAFIZ)" theme="success" icon="fas fa-calendar-plus"
                    data-toggle="modal" data-target="#modalGetJadwal" />
            </x-adminlte-card>
        </div>
    </div>

    {{-- Modal Update Jadwal --}}
    <x-adminlte-modal id="modalJadwal" title="Jadwal Praktek" theme="warning" icon="fas fa-calendar-alt">
        <form name="formUpdateJadwal" id="formUpdateJadwal" action="{{ route('jadwaldokter.store') }}" method="POST">
            @csrf
            <input type="hidden" name="method" value="UPDATE">
            <input type="hidden" class="idjadwal" name="idjadwal" id="idjadwal">
            <x-adminlte-select2 name="kodesubspesialis" id="kodesubspesialis" label="Poliklinik">
                @foreach ($poli as $item)
                    <option value="{{ $item->kodesubspesialis }}">{{ $item->kodesubspesialis }} -
                        {{ $item->namasubspesialis }}
                    </option>
                @endforeach
            </x-adminlte-select2>
            <div class="row">
                <div class="col-md-6">
                    <x-adminlte-select2 name="hari" id="hari" label="Hari">
                        <option value="1">SENIN</option>
                        <option value="2">SELASA</option>
                        <option value="3">RABU</option>
                        <option value="4">KAMIS</option>
                        <option value="5">JUMAT</option>
                        <option value="6">SABTU</option>
                        <option value="0">MINGGU</option>
                    </x-adminlte-select2>
                </div>
                <div class="col-md-6">
                    <x-adminlte-input name="jadwal" label="Jadwal Praktek" placeholder="Jadwal Praktek"
                        enable-old-support />
                </div>
                <div class="col-md-6">
                    <x-adminlte-input name="kapasitaspasien" label="Kapasitas Pasien" placeholder="Kapasitas Pasien"
                        enable-old-support />
                </div>
                <div class="col-md-6">
                    {{-- <input type="hidden" name="libur" id="libur" value="0"> --}}
                    <x-adminlte-input-switch name="libur" label="Libur" data-on-text="YES" data-off-text="NO"
                        data-on-color="primary" />
                </div>
            </div>
            <x-adminlte-select2 name="kodedokter" id="kodedokter" label="Dokter">
                @foreach ($dokters as $item)
                    <option value="{{ $item->kodedokter }}">{{ $item->namadokter }}
                    </option>
                @endforeach
            </x-adminlte-select2>
        </form>
        <form name="formDeleteJadwal" id="formDeleteJadwal" action="{{ route('jadwaldokter.store') }}" method="POST">
            @csrf
            <input type="hidden" name="method" value="DELETE">
            <input type="hidden" class="idjadwal" name="idjadwal" id="idjadwal">
        </form>

        <x-slot name="footerSlot">
            <x-adminlte-button label="Update" form="formUpdateJadwal" class="mr-auto withLoad" type="submit" theme="success"
                icon="fas fa-edit" />
            <x-adminlte-button label="Hapus" form="formDeleteJadwal" class="withLoad" type="submit" theme="danger"
                icon="fas fa-trash-alt" />
            <x-adminlte-button theme="danger" icon="fas fa-times" label="Close" data-dismiss="modal" />
        </x-slot>
    </x-adminlte-modal>

    {{-- Modal Update Jadwal --}}
    <x-adminlte-modal id="modalAddJadwal" title="Tambah Jadwal Praktek" theme="success" icon="fas fa-calendar-alt">
        <form name="formAddJadwal" id="formAddJadwal" action="{{ route('jadwaldokter.store') }}" method="POST">
            @csrf
            <input type="hidden" name="method" value="STORE">
            {{-- <input type="hidden" class="idjadwal" name="idjadwal" id="idjadwal"> --}}
            <x-adminlte-select2 name="kodesubspesialis1" id="kodesubspesialis1" label="Poliklinik">
                @foreach ($poli as $item)
                    <option value="{{ $item->kodesubspesialis }}">{{ $item->kodesubspesialis }} -
                        {{ $item->namasubspesialis }}
                    </option>
                @endforeach
            </x-adminlte-select2>
            <div class="row">
                <div class="col-md-6">
                    <x-adminlte-select2 name="hari" id="hari" label="Hari">
                        <option value="1">SENIN</option>
                        <option value="2">SELASA</option>
                        <option value="3">RABU</option>
                        <option value="4">KAMIS</option>
                        <option value="5">JUMAT</option>
                        <option value="6">SABTU</option>
                        <option value="0">MINGGU</option>
                    </x-adminlte-select2>
                </div>
                <div class="col-md-6">
                    <x-adminlte-input name="jadwal" label="Jadwal Praktek" placeholder="Jadwal Praktek (HH.MM-HH.MM)" value="00.00-12.00"
                        enable-old-support />
                </div>
                <div class="col-md-6">
                    <x-adminlte-input name="kapasitaspasien" label="Kapasitas Pasien" placeholder="Kapasitas Pasien"
                        enable-old-support />
                </div>
                <div class="col-md-6">
                    {{-- <input type="hidden" name="libur" id="libur" value="0"> --}}
                    <x-adminlte-input-switch name="libur1" label="Libur" data-on-text="YES" data-off-text="NO"
                        data-on-color="primary" />
                </div>
            </div>
            <x-adminlte-select2 name="kodedokter1" id="kodedokter1" label="Dokter">
                @foreach ($dokters as $item)
                    <option value="{{ $item->kodedokter }}">{{ $item->namadokter }}
                    </option>
                @endforeach
            </x-adminlte-select2>
        </form>
        <x-slot name="footerSlot">
            <x-adminlte-button label="Simpan" form="formAddJadwal" class="mr-auto withLoad" type="submit" theme="success"
                icon="fas fa-save" />
            <x-adminlte-button theme="danger" icon="fas fa-times" label="Close" data-dismiss="modal" />
        </x-slot>
    </x-adminlte-modal>

    {{-- Modal Get Jadwal --}}
    <x-adminlte-modal id="modalGetJadwal" title="Tambah Jadwal Praktek (HAFIZ)" theme="success" icon="fas fa-calendar-plus">
        <form name="formJadwalHafiz" id="formJadwalHafiz" action="{{ route('jadwaldokter.store') }}" method="post">
            @csrf
            <input type="hidden" name="method" value="GET">
            @php
                $config = ['format' => 'YYYY-MM-DD'];
            @endphp
            <x-adminlte-input-date name="tanggalperiksa" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                label="Tanggal Periksa" :config="$config" />
            <x-adminlte-select2 name="kodepoli" id="kodepoli" label="Poliklinik">
                @foreach ($poli as $item)
                    @if ($item->kodedpoli == $item->kodesubspesialis)
                        <option value="{{ $item->kodedpoli }}">{{ $item->kodedpoli }} -
                            {{ $item->namapoli }}
                        </option>
                    @endif
                @endforeach
                @foreach ($poli as $item)
                    @if ($item->kodedpoli != $item->kodesubspesialis)
                        <option value="{{ $item->kodesubspesialis }}">{{ $item->kodesubspesialis }} -
                            {{ $item->namasubspesialis }}
                        </option>
                    @endif
                @endforeach
            </x-adminlte-select2>
            <x-slot name="footerSlot">
                <x-adminlte-button label="Tambah" form="formJadwalHafiz" class="mr-auto" type="submit"
                    theme="success" icon="fas fa-plus" />
                <x-adminlte-button theme="danger" label="Close" data-dismiss="modal" />
            </x-slot>
        </form>
    </x-adminlte-modal>
@stop

@section('plugins.Select2', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Datatables', true)
@section('plugins.BootstrapSwitch', true)
@section('js')
    <script>
        $(function() {
            $('.btnJadwal').click(function() {
                var jadwalid = $(this).data('id');
                $.LoadingOverlay("show");
                $.get("{{ route('jadwaldokter.index') }}" + '/' + jadwalid + '/edit', function(data) {
                    console.log(data);
                    $('#kodesubspesialis').val(data.kodesubspesialis).trigger('change');
                    $('#kodedokter').val(data.kodedokter).trigger('change');
                    $('#hari').val(data.hari).trigger('change');
                    $('#kapasitaspasien').val(data.kapasitaspasien);
                    $('#jadwal').val(data.jadwal);
                    $('.idjadwal').val(data.id);
                    $.LoadingOverlay("hide", true);
                    if (data.libur == 1) {
                        $('#libur').prop('checked', true).trigger('change');
                    }
                    $('#modalJadwal').modal('show');
                })

            });
        });
    </script>
@endsection
