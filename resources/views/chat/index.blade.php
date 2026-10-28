<h1>CS Toko Buku Petra</h1>

@if (session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

@foreach ($chats as $chat)
    <p><b>Anda:</b> {{ $chat->message }}</p>
    <p><b>AI:</b> {{ $chat->balasan }}</p>
    <hr>
@endforeach

<form method="POST" action="/chat">
    @csrf
    <input name="message" value="{{ old('message') }}" placeholder="Tulis pertanyaan..." style="width:300px" required>
    <button type="submit">Kirim</button>
    @error('message')
        <p style="color:red">{{ $message }}</p>
    @enderror
</form>