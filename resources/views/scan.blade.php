<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SmartWaste - Scan Sampah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1 class="text-center mb-4">Edukasi Scan Sampah</h1>

    {{-- Form Upload --}}
    <div class="card shadow p-4 mb-4">
        <form action="{{ route('scan.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Pilih Gambar Sampah</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Scan</button>
        </form>
    </div>

    {{-- Hasil Klasifikasi --}}
    @if(isset($label))
    <div class="card shadow p-4">
        <h4 class="mb-3">Hasil Klasifikasi</h4>
        
        @if(!empty($imageUrl))
        <div class="text-center mb-3">
            <img src="{{ $imageUrl }}" alt="Hasil Scan" class="img-fluid rounded" style="max-height: 250px;">
        </div>
        @endif

        <p><strong>Label:</strong> {{ ucfirst($label) }}</p>
        {{-- <p><strong>Deskripsi:</strong> {{ $confidence }}</p> --}}
        <p><strong>Deskripsi:</strong> {{ $description }}</p>
        <p><strong>Cara Daur Ulang:</strong> {{ $recycle }}</p>
        <p><strong>Dampak:</strong> {{ $impact }}</p>
    </div>
    @endif

</div>

</body>
</html>
