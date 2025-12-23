<table>
    {{-- KOP SURAT --}}
    <thead>
    <tr>
        {{-- COLSPAN DIUBAH JADI 7 (Awalnya 10, dikurangi 3 kolom yang dihapus) --}}
        <th colspan="7" style="text-align: center; font-family: Arial; font-size: 14px; font-weight: bold;">
            PEMERINTAH KABUPATEN BONE BOLANGO
        </th>
    </tr>
    <tr>
        <th colspan="7" style="text-align: center; font-family: Arial; font-size: 16px; font-weight: bold;">
            DINAS PENANAMAN MODAL PELAYANAN TERPADU SATU PINTU
        </th>
    </tr>
    <tr>
        <th colspan="7" style="text-align: center; font-family: Arial; font-size: 10px; font-style: italic;">
            Pusat Pemerintahan Jln. Prof. DR. Ing BJ. Habibie Desa Ulantha Kecamatan Suwawa
        </th>
    </tr>
    <tr>
        <th colspan="7" style="border-bottom: 3px double #000000;"></th>
    </tr>
    <tr>
        <th colspan="7"></th>
    </tr>

    <tr>
        <th colspan="7" style="text-align: center; faont-family: Arial; font-size: 12px; font-weight: bold;">
            REKAPITULASI PENGAJUAN DOKUMEN IZIN KESEHATAN
        </th>
    </tr>
    <tr>
        <th colspan="7" style="text-align: center; font-family: Arial; font-size: 12px; font-weight: bold;">
            SELANG BULAN {{ $bulan }} TAHUN {{ $tahun }}
        </th>
    </tr>
    </thead>

    {{-- LOOPING PER JENIS IZIN (Tetap Pertahankan Grouping Ini) --}}
    @foreach($groupedData as $jenisIzin => $items)

        {{-- Spasi antar tabel --}}
        <tr><th colspan="7"></th></tr>

        {{-- JUDUL JENIS IZIN --}}
        <tr>
            {{-- Colspan disesuaikan jadi 7 --}}
            <th colspan="7" style="text-align: left; font-family: Arial; font-size: 12px; font-weight: bold; background-color: #f0f0f0; border: 1px solid #000000;">
                KATEGORI: {{ strtoupper($jenisIzin) }} (Total: {{ count($items) }})
            </th>
        </tr>

        {{-- HEADER TABEL --}}
        <tr>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">NO</th>

            {{-- [DIHAPUS] TANGGAL MASUK --}}

            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">NAMA PEMOHON</th>

            {{-- [DIHAPUS] ALAMAT PEMOHON --}}

            <th colspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">REKOMENDASI</th>

            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">TEMPAT PRAKTEK</th>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">NOMOR IZIN</th>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">TANGGAL TERBIT</th>

            {{-- [DIHAPUS] NO. TLPN --}}
        </tr>
        <tr>
            {{-- Sub-Header Rekomendasi --}}
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #D9D9D9;">NOMOR</th>
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #D9D9D9;">TANGGAL</th>
        </tr>

        {{-- DATA ROWS --}}
        <tbody>
        @foreach($items as $index => $row)
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>

                {{-- [DIHAPUS] Tanggal Masuk --}}

                {{-- Nama Pemohon --}}
                <td style="border: 1px solid #000000;">{{ $row->user->name ?? '-' }}</td>

                {{-- [DIHAPUS] Alamat Pemohon --}}

                {{-- Rekomendasi: Nomor --}}
                <td style="border: 1px solid #000000;">{{ $row->nomor_surat ?? '-' }}</td>

                {{-- Rekomendasi: Tanggal --}}
                <td style="border: 1px solid #000000;">
                    {{ $row->tgl_surat ? \Carbon\Carbon::parse($row->tgl_surat)->format('d M Y') : '-' }}
                </td>

                {{-- Tempat Praktek --}}
                <td style="border: 1px solid #000000;">{{ $row->tempat_praktek ?? '-' }}</td>

                {{-- Nomor Izin --}}
                <td style="border: 1px solid #000000;">{{ $row->nomor_izin ?? 'Belum Terbit' }}</td>

                {{-- Tanggal Terbit --}}
                <td style="border: 1px solid #000000;">
                    {{ $row->tgl_terbit ? \Carbon\Carbon::parse($row->tgl_terbit)->format('d M Y') : '-' }}
                </td>

                {{-- [DIHAPUS] Nomor Telepon --}}
            </tr>
        @endforeach
        </tbody>

    @endforeach
</table>
