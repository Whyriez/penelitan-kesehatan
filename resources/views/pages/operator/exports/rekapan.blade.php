<table>
    <thead>
    {{-- KOP SURAT --}}
    <tr>
        <th colspan="12" style="text-align: center; font-family: Arial; font-size: 14px; font-weight: bold;">
            PEMERINTAH KABUPATEN BONE BOLANGO
        </th>
    </tr>
    <tr>
        <th colspan="12" style="text-align: center; font-family: Arial; font-size: 16px; font-weight: bold;">
            DINAS PENANAMAN MODAL PELAYANAN TERPADU SATU PINTU
        </th>
    </tr>
    <tr>
        <th colspan="12" style="text-align: center; font-family: Arial; font-size: 10px; font-style: italic;">
            Pusat Pemerintahan Jln. Prof. DR. Ing BJ. Habibie Desa Ulantha Kecamatan Suwawa
        </th>
    </tr>
    <tr>
        <th colspan="12" style="border-bottom: 3px double #000000;"></th>
    </tr>
    <tr>
        <th colspan="12"></th>
    </tr> {{-- Spasi --}}

    {{-- JUDUL LAPORAN --}}
    <tr>
        <th colspan="12" style="text-align: center; font-family: Arial; font-size: 12px; font-weight: bold;">
            REKAPITULASI PENGAJUAN DOKUMEN IZIN KESEHATAN
        </th>
    </tr>
    <tr>
        <th colspan="12" style="text-align: center; font-family: Arial; font-size: 12px; font-weight: bold;">
            SELANG BULAN {{ $bulan }} TAHUN {{ $tahun }}
        </th>
    </tr>
    <tr>
        <th colspan="12"></th>
    </tr> {{-- Spasi --}}

    {{-- HEADER TABEL (SESUAI GAMBAR) --}}
    <tr>
        <th rowspan="2"
            style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">
            NO
        </th>
        <th rowspan="2"
            style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">
            TANGGAL MASUK
        </th>
        <th rowspan="2"
            style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">
            NAMA PEMOHON
        </th>
        <th rowspan="2"
            style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">
            ALAMAT PEMOHON
        </th>
        <th rowspan="2"
            style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">
            JENIS IZIN
        </th>

        {{-- MERGED HEADER REKOMENDASI --}}
        <th colspan="2"
            style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">
            REKOMENDASI
        </th>

        <th rowspan="2"
            style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">
            TEMPAT PRAKTEK
        </th>
        <th rowspan="2"
            style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">
            NOMOR IZIN
        </th>
        <th rowspan="2"
            style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">
            TANGGAL TERBIT
        </th>
        <th rowspan="2"
            style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">
            NO. TLPN
        </th>
        <th rowspan="2"
            style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">
            STATUS
        </th>
    </tr>
    <tr>
        {{-- SUB HEADER REKOMENDASI --}}
        <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #D9D9D9;">NOMOR
        </th>
        <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #D9D9D9;">
            TANGGAL
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $index => $row)
        <tr>
            <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000000; text-align: center;">{{ $row->created_at->format('d M Y') }}</td>
            <td style="border: 1px solid #000000;">{{ $row->user->name ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $row->user->alamat ?? '-' }}</td>
            <td style="border: 1px solid #000000;">
                {{ $row->jenisIzin->nama ?? 'Izin Umum' }}
            </td>

            {{-- UPDATE DI SINI: Kolom Rekomendasi (Nomor & Tanggal) --}}
            <td style="border: 1px solid #000000;">{{ $row->nomor_surat ?? '-' }}</td>
            <td style="border: 1px solid #000000;">
                {{ $row->tgl_surat ? \Carbon\Carbon::parse($row->tgl_surat)->format('d M Y') : '-' }}
            </td>

            <td style="border: 1px solid #000000;">{{ $row->tempat_praktek ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $row->nomor_izin ?? 'Belum Terbit' }}</td>
            <td style="border: 1px solid #000000;">
                {{ $row->tgl_terbit ? \Carbon\Carbon::parse($row->tgl_terbit)->format('d M Y') : '-' }}
            </td>
            <td style="border: 1px solid #000000;">'{{ $row->user->nomor_telepon ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ ucfirst($row->status) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
