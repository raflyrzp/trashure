<?php

namespace App\Http\Controllers;

use App\Models\PointHistory;
use App\Models\Report;
use App\Models\WasteType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    // List reports:
    // - Admin: all
    // - Citizen: only own reports
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Report::with(['reporter', 'wasteType'])
            ->latest('reported_at');

        if (!$user->isAdmin()) {
            $query->where('reporter_id', $user->id);
        }

        $reports = $query->paginate(10);

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $wasteTypes = WasteType::orderBy('name')->get();
        return view('reports.create', compact('wasteTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'waste_type_id' => ['nullable', 'exists:waste_types,id'],
            'description'   => ['required', 'string', 'max:5000'],
            'location'      => ['required', 'string', 'max:255'],
            'photo'         => ['required', 'image', 'max:4096'],
        ], [
            'description.required' => 'Deskripsi wajib diisi.',
            'location.required'    => 'Lokasi wajib diisi.',
            'photo.required'       => 'Foto wajib diunggah.',
            'photo.image'          => 'File foto tidak valid.',
        ]);

        $photoPath = $request->file('photo')->store('reports', 'public');

        $report = Report::create([
            'reporter_id'     => $request->user()->id,
            'admin_id'        => null,
            'waste_type_id'   => $validated['waste_type_id'] ?? null,
            'description'     => $validated['description'],
            'location'        => $validated['location'],
            'photo_url'       => $photoPath,
            'status'          => Report::STATUS_PENDING,
            'reported_at'     => now(),
            'verified_at'     => null,
            'admin_notes'     => null,
        ]);

        return redirect()
            ->route('reports.show', $report)
            ->with('status', 'Laporan berhasil dikirim. Menunggu verifikasi admin.');
    }

    public function show(Request $request, Report $report)
    {
        $user = $request->user();

        if (!$user->isAdmin() && $report->reporter_id !== $user->id) {
            abort(403);
        }

        $report->load(['reporter', 'admin', 'wasteType', 'pointHistory']);

        return view('reports.show', compact('report'));
    }

    // Delete report (only owner & only when pending)
    public function destroy(Request $request, Report $report)
    {
        $user = $request->user();

        if ($report->reporter_id !== $user->id) {
            abort(403);
        }

        if ($report->status !== Report::STATUS_PENDING) {
            return back()->with('error', 'Laporan tidak dapat dihapus setelah diproses.');
        }

        if ($report->photo_url) {
            Storage::disk('public')->delete($report->photo_url);
        }

        $report->delete();

        return redirect()->route('reports.index')->with('status', 'Laporan berhasil dihapus.');
    }

    // ADMIN: update status + award/rollback points according to ERD
    public function updateStatus(Request $request, Report $report)
    {
        $user = $request->user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status'       => ['required', 'in:' . implode(',', Report::STATUSES)],
            'admin_notes'  => ['nullable', 'string', 'max:5000'],
        ]);

        DB::transaction(function () use ($report, $user, $validated) {
            $report->status = $validated['status'];
            $report->admin_notes = $validated['admin_notes'] ?? $report->admin_notes;
            $report->admin_id = $user->id;

            if (
                in_array($report->status, [Report::STATUS_VERIFIED, Report::STATUS_RESOLVED], true)
                && is_null($report->verified_at)
            ) {
                $report->verified_at = now();
            }

            $report->save();

            $pointsPerReport = 10;

            // Award if now awardable and not yet awarded
            if ($report->isAwardable() && !$report->pointHistory) {
                PointHistory::create([
                    'report_id'    => $report->id,
                    'user_id'      => $report->reporter_id,
                    'points'       => $pointsPerReport,
                    'granted_at'   => now(),
                ]);

                $report->reporter()->increment('total_points', $pointsPerReport);
            }

            // Revoke if moved to non-awardable and history exists
            if (
                !in_array($report->status, [Report::STATUS_VERIFIED, Report::STATUS_RESOLVED], true)
                && $report->pointHistory
            ) {
                $amount = $report->pointHistory->points;
                $report->reporter()->decrement('total_points', $amount);
                $report->pointHistory()->delete();
            }
        });

        return back()->with('status', 'Status laporan berhasil diperbarui.');
    }
}
