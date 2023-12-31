@extends('layouts.adminLayout.adminLayout')
@section('cssContent')

@endsection
@section('content')

@if($alternatif->isEmpty())
    <h2>Kamu belum mengisi Data Lansia</h2>
    <a href="{{ route('admin.alternatif.index') }}"><button>Isi Data Lansia</button></a>

@elseif ( $bobot->isEmpty())
<h2>Kamu belum mengisi Bobot</h2>
<a href="{{ route('admin.bobot.index') }}"><button>Isi Bobot</button></a>

<hr>

@elseif ($nilai_smart->isEmpty())
 {{-- form input nilai awal --}}

<table>
    <thead>
        <tr>
            <th>Data Lansia</th>
        @foreach ($kriteria as $kriteria)
            <th>{{ $kriteria->nama_kriteria }}</th>
        @endforeach
        </tr>
    </thead>
    <tbody>
        <form action="{{ route('admin.smart.store') }}" method="post">
            @csrf
                @foreach ($alternatif as $alternatif)
                    <tr>
                        <td>{{ $alternatif->nama_alternatif }}</td>
                        @for ($k = 0; $k < $jKriteria; $k++)
                            <td>
                                {{-- <input type="number" name="{{ $alternatif->id }}{{ $k }}nilai_awal" value="50" min="50" max="100" step="50" required> --}}
                                <select name="{{ $alternatif->id }}{{ $k }}nilai_awal" class="form-control" required>
                                    <option disabled selected>Pilih :</option>
                                    @foreach ($subkriteria as $sub)
                                            @if ($sub->id_kriteria == $k)                                            
                                                <option value="{{ $sub->bobot_subkriteria}}" >{{ $sub->nama_subkriteria}}</option>
                                            @endif
                                    @endforeach
                                </select>
                            </td>
                        @endfor
                            <input type="hidden" name="alternatif_id" value="{{ $alternatif->id }}">
                    </tr>
                @endforeach
        </tbody>
</table>
<hr>
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
        </form>
@else

@if (!$kosong->isEmpty())
<hr>
<h4>Nama Lansia yang Belum diisi Nilai</h4>
<table>
    <thead>
        <tr>
            <th>Nama Lansia</th>
        @foreach ($kriteria as $kriteria)
            <th>{{ $kriteria->nama_kriteria }}</th>
        @endforeach
        </tr>
    </thead>
    <tbody>
        <form action="{{ route('admin.smart.databaru') }}" method="post">
            @csrf
                @foreach ($kosong as $alternatif)
                    <tr>
                        <td>{{ $alternatif->nama_alternatif }}</td>
                        @for ($k = 0; $k < $jKriteria; $k++)
                            <td>
                                {{-- <input type="number" name="{{ $alternatif->id }}{{ $k }}nilai_awal" value="1" min="1" max="10" required> --}}
                                <select name="{{ $alternatif->id }}{{ $k }}nilai_awal" class="form-control" required>
                                    <option disabled selected>Pilih :</option>
                                    @foreach ($subkriteria as $sub)
                                            @if ($sub->id_kriteria == $k)                                            
                                                <option value="{{ $sub->bobot_subkriteria}}" >{{ $sub->nama_subkriteria}}</option>
                                            @endif
                                    @endforeach
                                </select>
                            </td>
                        @endfor
                            <input type="hidden" name="alternatif_id" value="{{ $alternatif->id }}">
                    </tr>
                @endforeach
        </tbody>
</table>
<hr>
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
        </form>

@endif
<hr>
<h4>Ranking</h4>
<table>
    <thead>
        <tr>
            <th>Alternatif</th>
            <th>Kampus</th>
            <th>Hasil Akhir</th>
            <th>Ranking</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ranking->sortBy('ranking') as $ranking)
            <tr>
                <td>{{ $ranking->Alternatif->nama_alternatif }}</td>
                <td>{{ $ranking->Alternatif->nama_kampus }}</td>
                <td>{{ $ranking->hasil_akhir }}</td>
                <td>{{ $ranking->ranking }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


<hr>
<h3>Rincian Nilai</h3>
<hr>
        <table>
            <thead>
                <tr>
                    <th>Alternatif</th>
                    <th>Kampus</th>
                    <th>Kriteria</th>
                    <th>Nilai Awal</th>
                    <th>Nilai Utility</th>
                    <th>Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($nilai_smart->sortBy('m_alternatif_id') as $nilai)
                    <tr>
                        <td>{{ $nilai->Alternatif->nama_alternatif }}</td>
                        <td>{{ $nilai->Alternatif->nama_kampus }}</td>
                        <td>{{ $nilai->Kriteria->nama_kriteria }}</td>

                        <td>{{ $nilai->nilai_awal }} <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editnilai-{{ $nilai->m_alternatif_id }}{{ $nilai->m_kriteria_id }}">Edit</button></td>

                        {{-- form edit --}}
                        <div class="modal fade" id="editnilai-{{ $nilai->m_alternatif_id }}{{ $nilai->m_kriteria_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Nilai Awal</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>

                                </div>
                                <div class="modal-body">
                                <p>Ubah nilai kriteria {{ $nilai->Kriteria->nama_kriteria }} untuk  {{ $nilai->Alternatif->nama_alternatif }}</p>
                                <form method="POST" action="{{ route('admin.smart.update', $nilai->m_alternatif_id) }}">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="form-group">
                                        <label for="nama-alternatif" class="col-form-label">Nilai Awal:</label>
                                        <input type="number" class="form-control" id="nama-alternatif" name="{{ $nilai->m_alternatif_id }}{{ $nilai->m_kriteria_id }}nilai_awal" value="{{ $nilai->nilai_awal }}" min="50" max="100" step="50" required>
                                        <input type="hidden" name="kriteria_id" value="{{ $nilai->m_kriteria_id }}">
                                    </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary" value="submit">Simpan</button>
                                </div>
                            </form>
                            </div>
                            </div>
                        </div>



                        <td>{{ $nilai->nilai_utility }}</td>
                        <td>{{ $nilai->nilai_akhir }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


@endif
@endsection
@section('jsContent')

@endsection
