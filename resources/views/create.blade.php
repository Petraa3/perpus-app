<h1>Tambah Buku</h1>

@if ($errors->any())
    <ul style="color:red">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="/buku">
    @csrf
    <p>Judul: <input name="judul" value="{{ old('judul') }}"></p>
    <p>Penulis: <input name="penulis" value="{{ old('penulis') }}"></p>
    <p>Tahun: <input name="tahun_terbit" value="{{ old('tahun_terbit') }}"></p>
    <p>Stok: <input name="stok" value="{{ old('stok', 0) }}"></p>
    <button type="submit">Simpan</button>
</form>