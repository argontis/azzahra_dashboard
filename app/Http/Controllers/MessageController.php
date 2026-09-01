<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Tampilkan semua pesan yang relevan untuk user yang sedang login.
     * Jika request Accept: application/json, kembalikan JSON untuk FAB panel.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $myLevel = $user->kry_level;
        $limit = (int) $request->query('limit', 0);

        $query = Message::where('target_role', 'all')
            ->orWhere('target_role', $myLevel)
            ->latest();

        if ($limit > 0) {
            $query->limit($limit);
        }

        $messages = $query->get();

        // Jika request adalah AJAX / Accept JSON → kembalikan JSON untuk FAB panel
        if ($request->wantsJson() || $request->header('Accept') === 'application/json') {
            $readIds = MessageRead::where('reader_id', $user->kry_kode)
                ->pluck('message_id')
                ->toArray();

            return response()->json(
                $messages->map(function (Message $msg) use ($readIds) {
                    return [
                        'id' => $msg->id,
                        'judul' => $msg->judul,
                        'isi' => $msg->isi,
                        'sender_nama' => $msg->sender_nama,
                        'target_role' => $msg->target_role,
                        'created_at' => $msg->created_at->toIso8601String(),
                        'is_unread' => ! in_array($msg->id, $readIds),
                    ];
                })
            );
        }

        // Tandai semua pesan relevan sebagai sudah dibaca (hanya untuk halaman penuh)
        foreach ($messages as $msg) {
            MessageRead::firstOrCreate([
                'message_id' => $msg->id,
                'reader_id' => $user->kry_kode,
            ], [
                'read_at' => now(),
            ]);
        }

        return view('messages.index', compact('messages'));
    }

    /**
     * Kirim pesan baru ke semua role atau role tertentu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'target_role' => 'required|string',
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $user = Auth::user();

        Message::create([
            'sender_id' => $user->kry_kode,
            'sender_nama' => $user->kry_nama,
            'sender_level' => $user->kry_level,
            'target_role' => $request->target_role,
            'judul' => $request->judul,
            'isi' => $request->isi,
        ]);

        return back()->with('sukses', 'Pesan berhasil dikirim!');
    }

    /**
     * Jumlah pesan yang belum dibaca oleh user yang sedang login.
     * Digunakan oleh layout untuk menampilkan badge.
     */
    public static function unreadCount(): int
    {
        if (! Auth::check()) {
            return 0;
        }

        $user = Auth::user();
        $myLevel = $user->kry_level;

        // Ambil ID semua pesan yang relevan
        $relevantIds = Message::where('target_role', 'all')
            ->orWhere('target_role', $myLevel)
            ->pluck('id');

        // Hitung yang belum ada di message_reads untuk user ini
        $readIds = MessageRead::where('reader_id', $user->kry_kode)
            ->whereIn('message_id', $relevantIds)
            ->pluck('message_id');

        return $relevantIds->diff($readIds)->count();
    }
}
