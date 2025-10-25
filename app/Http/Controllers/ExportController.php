<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportController extends Controller
{
    public function schedulesPdf()
    {
        if (!Schema::hasTable('schedules')) {
            return back()->withErrors(['export' => 'No schedules table found. Migrate the database first.']);
        }

        $schedules = Schedule::query()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $pdf = Pdf::loadView('exports.schedules_pdf', ['rows' => $schedules])
            ->setPaper('a4', 'portrait');

        $filename = 'schedules_' . now()->format('Ymd_His') . '.pdf';
        $content = $pdf->output();

        return new Response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    public function schedulesExcel()
    {
        $rows = collect();
        if (Schema::hasTable('schedules')) {
            $rows = \DB::table('schedules')->limit(100)->get();
        }
        return Excel::download(new class($rows) implements FromCollection {
            public function __construct(private Collection $rows) {}
            public function collection(): Collection { return $this->rows; }
        }, 'schedules.xlsx');
    }
}