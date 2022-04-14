@extends('adminlte::page')

@section('title', 'Tambah Antrian Pasien Baru Offline')

@section('content_header')
    <h1>Tambah Antrian Pasien Baru Offline</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Isilah Data Berikut Untuk Pendaftaran Antrian Online" theme="info"
                icon="fas fa-info-circle" collapsible maximizable>
                <div class="row">
                    <div class="col-md-3">
                        <dl>
                            <dt>Kode Booking</dt>
                            <dd>{{ $antrian->kodebooking }}</dd>
                            <dt>Antrian</dt>
                            <dd>{{ $antrian->angkaantrean }} / {{ $antrian->nomorantrean }}</dd>
                            <dt>Jenis Pasien</dt>
                            <dd>{{ $antrian->jenispasien }}</dd>
                            <dt>Administrator</dt>
                            <dd>{{ $antrian->user }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-3">
                        <dl>
                            <dt>NIK</dt>
                            <dd>{{ $antrian->nik }}</dd>
                            <dt>Nomor HP</dt>
                            <dd>{{ $antrian->nohp }}</dd>
                            <dt>Nomor Kartu</dt>
                            <dd>{{ isset($antrian->nomorkartu) ? $antrian->nomorkartu : '-' }}</dd>
                            <dt>Nomor Referensi / Rujukan</dt>
                            <dd>{{ isset($antrian->nomorreferensi) ? $antrian->nomorreferensi : '-' }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-3">
                        <dl>
                            <dt>Jenis Kunjungan</dt>
                            <dd>{{ $antrian->jeniskunjungan }}</dd>
                            <dt>Tanggal Periksa</dt>
                            <dd>{{ $antrian->tanggalperiksa }}</dd>
                            <dt>Poliklinik</dt>
                            <dd>{{ $antrian->kodepoli }} - {{ $antrian->namapoli }}</dd>
                            <dt>Dokter</dt>
                            <dd>{{ $antrian->kodedokter }} {{ $antrian->namadokter }} <br>
                                {{ $antrian->jampraktek }}
                            </dd>
                        </dl>
                    </div>
                </div>
                <form action="{{ route('antrian.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <x-adminlte-input name="nomorkartu" value="{{ $antrian->nomorkartu }}" label="Nomor Kartu"
                                placeholder="Nomor Kartu" enable-old-support />
                        </div>
                        <div class="col-md-4">
                            <x-adminlte-input name="nik" value="{{ $antrian->nik }}" label="NIK"
                                placeholder="Nomor Induk Kependudukan" enable-old-support />
                        </div>
                        <div class="col-md-4">
                            <x-adminlte-input name="nomorkk" label="Nomor KK" placeholder="Nomor Kartu Keluarga"
                                enable-old-support />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <x-adminlte-select id="jeniskelamin" name="jeniskelamin" label="Jenis Kelamin"
                                enable-old-support>
                                <option disabled selected>PILIH JENIS KELAMIN</option>
                                <option value="L">LAKI-LAKI</option>
                                <option value="P">PEREMPUAN</option>
                            </x-adminlte-select>
                        </div>
                        <div class="col-md-4">
                            @php
                                $config = ['format' => 'YYYY-MM-DD'];
                            @endphp
                            <x-adminlte-input-date name="tanggallahir" value="" label="Tanggal Lahir"
                                placeholder="Tanggal Lahir" :config="$config" enable-old-support />
                        </div>
                        <div class="col-md-4">
                            <x-adminlte-input name="nohp" value="{{ $antrian->nohp }}" label="Nomor HP"
                                placeholder="Nomor HP Yang Dapat Dihubungi" enable-old-support />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <x-adminlte-input name="alamat" label="Alamat" placeholder="Alamat" enable-old-support />
                        </div>
                        <div class="col-md-2">
                            <x-adminlte-input name="rt" label="Nomor RT" placeholder="Nomor RT" enable-old-support />
                        </div>
                        <div class="col-md-2">
                            <x-adminlte-input name="rw" label="Nomor RW" placeholder="Nomor RW" enable-old-support />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <x-adminlte-select2 name="namaprop" id="namaprop" label="Provonsi">
                                <option value="" disabled selected>PILIH PROVINSI</option>
                            </x-adminlte-select2>
                        </div>
                        <div class="col-md-3">
                            <x-adminlte-select2 name="namadati2" id="namadati2" label="Kota / Kabupaten">
                                <option value="" disabled selected>PILIH PROVINSI</option>
                            </x-adminlte-select2>
                        </div>
                        <div class="col-md-3">
                            <x-adminlte-select2 name="namakec" id="namakec" label="Kecamatan">
                                <option value="" disabled selected>PILIH PROVINSI</option>
                            </x-adminlte-select2>
                        </div>
                        <div class="col-md-3">
                            <x-adminlte-select2 name="namakel" id="namakel" label="Kelurahan / Desa">
                                <option value="" disabled selected>PILIH PROVINSI</option>
                            </x-adminlte-select2>
                        </div>
                    </div>
                    <x-adminlte-button label="Daftar" type="submit" theme="success" icon="fas fa-plus" />
                    <x-adminlte-button label="Reset" theme="danger" icon="fas fa-ban" />
                </form>
            </x-adminlte-card>
        </div>
    </div>
@stop

@section('plugins.Select2', true)
@section('plugins.TempusDominusBs4', true)

@section('js')
    <script>
        $(function() {
            $("#kodepoli").change(function() {
                var url = 'http://127.0.0.1:8000/api/antrian/ref/jadwal';
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        kodepoli: $("#kodepoli").val(),
                        tanggalperiksa: $("#tanggalperiksa").val(),
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.metadata.code != 200) {
                            $("#kodedokter").empty();
                            alert(
                                "Jadwal Dokter Poliklinik pada tanggal tersebut tidak tersedia"
                            );
                            return false;
                        } else {
                            $("#kodedokter").empty();
                            $.each(data.response, function(item) {
                                $('#kodedokter').append($('<option>', {
                                    value: data.response[item]
                                        .kodedokter,
                                    text: data.response[item].jadwal +
                                        ' - ' + data.response[item]
                                        .namadokter
                                }));
                            })
                        }
                    }
                });
            });
        });
    </script>
@endsection
