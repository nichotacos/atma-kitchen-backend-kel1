<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Bahan Baku</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Boostrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
        rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" 
        crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootsrap-icons.css">
    <style>
        body {
            background: #d3d3d3;
        }
        .main {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form {
            background: #fff;
            padding: 50px 30px;
        }
    </style>
</head>
<body>
        <h3 class="text-center mb-4">Data Bahan Baku</h3>

            <div class="container">
                <button type="button" class="btn btn-success">Tambah +</button>
                <div class="row">
                    <table class="table">
                        <thead>
                            <tr class="text-center mb-4">
                                <th scope="col">ID Bahan Baku</th>
                                <th scope="col">ID Unit</th>
                                <th scope="col">Nama Bahan Baku</th>
                                <th scope="col">Stok Bahan Baku</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        @foreach ($data as $row)
                        <tbody>
                            <tr class="text-center mb-4">
                                <th scope="row">{{ $row->id_bahan_baku }}</td>
                                <td>{{ $row->id_unit }}</td>
                                <td>{{ $row->nama_bahan_baku }}</td>
                                <td>{{ $row->stok_bahan_baku }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger">Delete</button>
                                    <button type="button" class="btn btn-info">Edit</button>
                                </td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
            </div>

</body>
</html>