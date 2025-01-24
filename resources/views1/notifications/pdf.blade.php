<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Barang</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f4f4f4; }
        .badge { padding: 5px 10px; border-radius: 3px; }
        .bg-success { background-color: #28a745; color: white; }
        .bg-danger { background-color: #dc3545; color: white; }
        .bg-warning { background-color: #ffc107; color: black; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Laporan Notifikasi Barang</h1>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Barang</th>
                <th>Jenis Transaksi</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notifications as $notification)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $notification->item->name }}</td>
                    <td>
                        @if ($notification->type === 'increase')
                            <span class="badge bg-success">Masuk</span>
                        @else
                            <span class="badge bg-danger">Keluar</span>
                        @endif
                    </td>
                    <td>{{ $notification->quantity }}</td>
                    <td>
                        @if ($notification->status === 'pending')
                            <span class="badge bg-warning">Menunggu</span>
                        @elseif ($notification->status === 'approved')
                            <span class="badge bg-success">Disetujui</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>{{ $notification->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
