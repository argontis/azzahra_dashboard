<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementRead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $user = Auth::user();
        $kryKode = $user ? $user->kry_kode : null;

        $announcements = Announcement::with(['creator'])
            ->where('status', 'active')
            ->orderBy('id', 'desc')
            ->get();

        if ($request->wantsJson() || $request->ajax()) {
            $data = $announcements->map(function ($item) use ($kryKode) {
                $isRead = $kryKode ? $item->isReadBy($kryKode) : true;

                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'isi' => $item->isi,
                    'tipe' => $item->tipe,
                    'mulai_pada' => $item->mulai_pada ? $item->mulai_pada->format('d M Y H:i') : null,
                    'selesai_pada' => $item->selesai_pada ? $item->selesai_pada->format('d M Y H:i') : null,
                    'creator_nama' => $item->creator ? $item->creator->kry_nama : 'Admin',
                    'created_at' => $item->created_at->toIso8601String(),
                    'is_unread' => ! $isRead,
                ];
            });

            return response()->json($data);
        }

        return view('announcements.index', [
            'title' => 'Pemberitahuan Sistem',
            'announcements' => $announcements,
            'isAdmin' => $user && ($user->kry_level === 'Admin' || $user->kry_level === 'Pimpinan'),
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();
        if (! $user || ($user->kry_level !== 'Admin' && $user->kry_level !== 'Pimpinan')) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Hanya Admin yang dapat membuat pemberitahuan.'], 403);
            }

            return back()->with('gagal', 'Hanya Admin yang berwenang membuat pemberitahuan sistem.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tipe' => 'required|in:maintenance,penting,info',
            'mulai_pada' => 'nullable|date',
            'selesai_pada' => 'nullable|date',
        ]);

        $announcement = Announcement::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tipe' => $request->tipe,
            'mulai_pada' => $request->mulai_pada,
            'selesai_pada' => $request->selesai_pada,
            'created_by' => $user->kry_kode,
            'status' => 'active',
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $announcement]);
        }

        return redirect()->route('announcements.index')->with('sukses', 'Pemberitahuan berhasil dipublikasikan.');
    }

    public function destroy(int $id): RedirectResponse|JsonResponse
    {
        $user = Auth::user();
        if (! $user || ($user->kry_level !== 'Admin' && $user->kry_level !== 'Pimpinan')) {
            return back()->with('gagal', 'Tidak memiliki izin menghapus pemberitahuan.');
        }

        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('announcements.index')->with('sukses', 'Pemberitahuan berhasil dihapus.');
    }

    public function markAllRead(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false], 401);
        }

        $kryKode = $user->kry_kode;
        $activeAnnouncements = Announcement::where('status', 'active')->pluck('id');

        foreach ($activeAnnouncements as $annId) {
            AnnouncementRead::firstOrCreate([
                'announcement_id' => $annId,
                'kry_kode' => $kryKode,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['count' => 0]);
        }

        $kryKode = $user->kry_kode;
        $readIds = AnnouncementRead::where('kry_kode', $kryKode)->pluck('announcement_id');

        $unreadCount = Announcement::where('status', 'active')
            ->whereNotIn('id', $readIds)
            ->count();

        return response()->json(['count' => $unreadCount]);
    }
}
