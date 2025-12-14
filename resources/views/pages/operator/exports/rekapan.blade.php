<table>
    {{-- KOP SURAT --}}
    <thead>
    <tr>
        {{-- COLSPAN DIUBAH JADI 10 KARENA JUMLAH KOLOM REAL TABEL ADALAH 10 --}}
        <th colspan="10" style="text-align: center; font-family: Arial; font-size: 14px; font-weight: bold;">
            PEMERINTAH KABUPATEN BONE BOLANGO
        </th>
    </tr>
    <tr>
        <th colspan="10" style="text-align: center; font-family: Arial; font-size: 16px; font-weight: bold;">
            DINAS PENANAMAN MODAL PELAYANAN TERPADU SATU PINTU
        </th>
    </tr>
    <tr>
        <th colspan="10" style="text-align: center; font-family: Arial; font-size: 10px; font-style: italic;">
            Pusat Pemerintahan Jln. Prof. DR. Ing BJ. Habibie Desa Ulantha Kecamatan Suwawa
        </th>
    </tr>
    <tr>
        <th colspan="10" style="border-bottom: 3px double #000000;"></th>
    </tr>
    <tr>
        <th colspan="10"></th>
    </tr>

    <tr>
        <th colspan="10" style="text-align: center; faont-family: Arial; font-size: 12px; font-weight: bold;">
            REKAPITULASI PENGAJUAN DOKUMEN IZIN KESEHATAN
        </th>
    </tr>
    <tr>
        <th colspan="10" style="text-align: center; font-family: Arial; font-size: 12px; font-weight: bold;">
            SELANG BULAN {{ $bulan }} TAHUN {{ $tahun }}
        </th>
    </tr>
    </thead>

    {{-- LOOPING PER JENIS IZIN --}}
    @foreach($groupedData as $jenisIzin => $items)

        {{-- Spasi antar tabel --}}
        <tr><th colspan="10"></th></tr>

        {{-- JUDUL JENIS IZIN --}}
        <tr>
            <th colspan="10" style="text-align: left; font-family: Arial; font-size: 12px; font-weight: bold; background-color: #f0f0f0; border: 1px solid #000000;">
                KATEGORI: {{ strtoupper($jenisIzin) }} (Total: {{ count($items) }})
            </th>
        </tr>

        {{-- HEADER TABEL --}}
        <tr>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">NO</th>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">TANGGAL MASUK</th>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">NAMA PEMOHON</th>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">ALAMAT PEMOHON</th>

            <th colspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">REKOMENDASI</th>

            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">TEMPAT PRAKTEK</th>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">NOMOR IZIN</th>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">TANGGAL TERBIT</th>
            <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">NO. TLPN</th>

            {{-- KOLOM STATUS DIHAPUS DARI SINI --}}
        </tr>
        <tr>
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #D9D9D9;">NOMOR</th>
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #D9D9D9;">TANGGAL</th>
        </tr>

        {{-- DATA ROWS --}}
        <tbody>
        @foreach($items as $index => $row)
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $row->created_at->format('d M Y') }}</td>
                <td style="border: 1px solid #000000;">{{ $row->user->name ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $row->user->alamat ?? '-' }}</td>

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

                {{-- KOLOM STATUS DIHAPUS DARI SINI --}}
            </tr>
        @endforeach
        </tbody>

    @endforeach
</table>
