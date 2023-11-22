<!-- resources/views/list_buku.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Buku</title>
</head>
<body>
    <h1>List Buku</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Judul Buku</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bukus as $buku)
                <tr>
                    <td><img src="{{ asset('path/to/your/uploads/' . $buku->foto) }}" alt="{{ $buku->judul }}" width="100"></td>
                    <td>{{ $buku->judul }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
