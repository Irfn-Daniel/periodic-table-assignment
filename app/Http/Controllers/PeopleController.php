<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Reads the Google Sheet through the Sheets API (API key + sheet shared as
 * "Anyone with the link: Viewer", or swap in a service-account token) and
 * returns clean JSON for the front end.
 */
class PeopleController extends Controller
{
    public function index()
    {
        $people = Cache::remember('people', 60, fn () => $this->fetch());

        return response()->json($people);
    }

    private function fetch(): array
    {
        $id = config('services.sheets.id');
        $range = config('services.sheets.range', 'Sheet1!A1:F');

        $res = Http::get(
            "https://sheets.googleapis.com/v4/spreadsheets/{$id}/values/" . rawurlencode($range),
            ['key' => config('services.sheets.key')]
        )->throw()->json();

        $rows = $res['values'] ?? [];
        array_shift($rows); // header row (note: CSV header is " Net Worth " with spaces)

        return collect($rows)
            ->filter(fn ($r) => count($r) >= 6)
            ->map(fn ($r) => [
                'name'     => trim(preg_replace('/[^\P{C}\x{FFFD}]/u', '', $r[0])),
                'photo'    => trim($r[1]),
                'age'      => (int) $r[2],
                'country'  => trim($r[3]),
                'interest' => trim($r[4]),
                'netWorth' => (float) preg_replace('/[^0-9.\-]/', '', $r[5]),
            ])
            ->values()
            ->all();
    }
}
