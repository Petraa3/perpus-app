<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::latest()->take(20)->get()->reverse();
        return view('chat.index', ['chats' => $chats]);
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $daftarBuku = Buku::all()->map(function ($b) {
            return "- {$b->judul} oleh {$b->penulis} ({$b->tahun_terbit}), stok: {$b->stok}";
        })->implode("\n");

        $instruksi = "Kamu adalah customer service Toko Buku Petra. Jawab sopan, maksimal 3 kalimat.
Toko buka setiap hari pukul 09.00-21.00.
Jika ditanya hal di luar buku dan toko, tolak dengan halus dan arahkan kembali ke topik buku.
Berikut daftar buku yang tersedia:
{$daftarBuku}

Jawab HANYA berdasarkan data di atas. Jika informasi tidak ada, katakan dengan jujur bahwa kamu tidak tahu.";

        $model = config('services.gemini.model');

        try {
            $response = Http::withHeaders([
                'x-goog-api-key' => config('services.gemini.key'),
            ])->timeout(60)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent",
                [
                    'system_instruction' => [
                        'parts' => [['text' => $instruksi]],
                    ],
                    'contents' => [
                        ['role' => 'user', 'parts' => [['text' => $data['message']]]],
                    ],
                ]
            );
        } catch (\Exception $e) {
            logger()->error('Gagal terhubung ke Gemini: ' . $e->getMessage());
            return back()->with('error', 'Maaf, AI sedang tidak bisa dihubungi. Coba lagi nanti.');
        }

        if ($response->failed()) {
            logger()->error('Gemini error ' . $response->status() . ': ' . $response->body());
            return back()->with('error', 'Maaf, AI sedang bermasalah. Coba lagi nanti.');
        }

        $balasan = $response->json('candidates.0.content.parts.0.text')
            ?? 'Maaf, saya tidak bisa menjawab pertanyaan itu.';

        Chat::create([
            'message' => $data['message'],
            'balasan' => $balasan,
        ]);

        return redirect('/chat');
    }
}