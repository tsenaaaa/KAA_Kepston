<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use App\Models\Destinasi;

class DestinasiController extends BaseController
{
    public function index()
    {
        $list = Destinasi::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.destinasi.index', ['list' => $list]);
    }

    // helper: find column index by keywords (flexible matching)
    private function detectColumnIndex(array $headers, array $keywords)
    {
        foreach ($headers as $i => $h) {
            $norm = strtolower(preg_replace('/[^a-z0-9]/', '', $h));
            $headers[$i] = $norm;
        }

        // exact match any keyword
        foreach ($keywords as $k) {
            if (in_array($k, $headers)) {
                return array_search($k, $headers);
            }
        }

        // match header that contains all keywords (order-independent)
        foreach ($headers as $i => $h) {
            $ok = true;
            foreach ($keywords as $k) {
                if (strpos($h, $k) === false) { $ok = false; break; }
            }
            if ($ok) return $i;
        }

        // match header that contains any keyword (fallback)
        foreach ($headers as $i => $h) {
            foreach ($keywords as $k) {
                if (strpos($h, $k) !== false) return $i;
            }
        }

        return null;
    }

    // AJAX: preview parsed CSV rows
    public function csvPreview(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
                $rows = [];
                $origHeaders = null;
                $detectedMap = [];

        try {
            $handle = fopen($file->getRealPath(), 'r');
            if ($handle !== false) {
                $headers = null;
                $colMap = [];
                $count = 0;
                while (($row = fgetcsv($handle)) !== false && $count < 50) {
                    if (!$headers) {
                        $headers = $row;
                        $origHeaders = $headers;
                        foreach ($headers as $i => $h) {
                            $norm = strtolower(preg_replace('/[^a-z0-9]/', '', $h));
                            $colMap[$norm] = $i;
                        }
                        $detectedMap = $colMap;

                        // detect indices flexibly
                        $tsIdx = $this->detectColumnIndex($origHeaders, ['total','score']);
                        $rcIdx = $this->detectColumnIndex($origHeaders, ['review','count']);

                        // store detected indices names for debug
                        $detectedMap['_tsIdx'] = $tsIdx;
                        $detectedMap['_rcIdx'] = $rcIdx;

                        continue;
                    }

                    $title = $row[0] ?? null;
                    $ts = null;
                    $rc = null;
                    $ts = null;
                    if (isset($tsIdx) && $tsIdx !== null) {
                        $ts = $row[$tsIdx] ?? null;
                        if ($ts !== null) {
                            $ts = trim($ts);
                            $ts = str_replace(',', '.', $ts);
                            if (!is_numeric($ts)) $ts = null;
                            else $ts = (float) $ts;
                        }
                    }
                    $rc = null;
                    if (isset($rcIdx) && $rcIdx !== null) {
                        $rc = $row[$rcIdx] ?? null;
                        if ($rc !== null) {
                            $rc = trim($rc);
                            $rc = preg_replace('/[^0-9]/', '', $rc);
                            if ($rc === '') $rc = null; else $rc = intval($rc);
                        }
                    }

                    $rows[] = [
                        'title' => $title,
                        'totalScore' => $ts,
                        'reviewsCount' => $rc,
                    ];

                    $count++;
                }
                fclose($handle);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Parse error'], 400);
        }

        return response()->json(['rows' => $rows, 'headers' => $origHeaders, 'colMap' => $detectedMap]);
    }

    public function create()
    {
        return view('admin.destinasi.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'alamat' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'kategori' => 'nullable|string|max:100',
            'foto' => 'nullable|image|max:2048',
            'tiktok' => 'nullable|url',
            'csv_file' => 'nullable|file|mimes:csv,txt|max:5120',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('destinasi', 'public');
            $data['foto'] = '/storage/' . $path;
        }

        // parse CSV if provided: extract 'total score' and 'reviews count' from headers
        if ($request->hasFile('csv_file')) {
            try {
                $file = $request->file('csv_file');
                $handle = fopen($file->getRealPath(), 'r');
                if ($handle !== false) {
                    $headers = null;
                    $colMap = [];
                    while (($row = fgetcsv($handle)) !== false) {
                            if (!$headers) {
                                $headers = $row;
                                foreach ($headers as $i => $h) {
                                    $norm = strtolower(preg_replace('/[^a-z0-9]/', '', $h));
                                    $colMap[$norm] = $i;
                                }

                                // detect flexible indices
                                $tsIdx = $this->detectColumnIndex($headers, ['total','score']);
                                $rcIdx = $this->detectColumnIndex($headers, ['review','count']);

                                // read the first data row after header
                                $dataRow = fgetcsv($handle);
                                if ($dataRow !== false && is_array($dataRow)) {
                                    if (isset($tsIdx) && $tsIdx !== null && isset($dataRow[$tsIdx])) {
                                        $ts = $dataRow[$tsIdx] ?? null;
                                        if ($ts !== null) {
                                            $ts = trim($ts);
                                            $ts = str_replace(',', '.', $ts);
                                            if (is_numeric($ts)) {
                                                $data['rating'] = round(floatval($ts), 2);
                                            }
                                        }
                                    }

                                    if (isset($rcIdx) && $rcIdx !== null && isset($dataRow[$rcIdx])) {
                                        $rc = $dataRow[$rcIdx] ?? null;
                                        if ($rc !== null) {
                                            $rc = trim($rc);
                                            $rc = preg_replace('/[^0-9]/', '', $rc);
                                            if ($rc !== '') {
                                                $data['meta'] = array_merge($data['meta'] ?? [], ['reviews_count' => intval($rc)]);
                                            }
                                        }
                                    }
                                }

                                // only first data row considered
                                break;
                            }
                    }
                    fclose($handle);
                }
            } catch (\Exception $e) {
                // ignore CSV parsing errors
            }
        }

        $dest = Destinasi::create($data);

        return redirect()->route('admin.destinasi.index')->with('success', 'Destinasi dibuat.');
    }

    public function show($id)
    {
        $item = Destinasi::findOrFail($id);
        return view('admin.destinasi.show', ['data' => $item]);
    }

    public function edit($id)
    {
        $item = Destinasi::findOrFail($id);
        return view('admin.destinasi.edit', ['data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = Destinasi::findOrFail($id);
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'alamat' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'kategori' => 'nullable|string|max:100',
            'foto' => 'nullable|image|max:2048',
            'tiktok' => 'nullable|url',
            'csv_file' => 'nullable|file|mimes:csv,txt|max:5120',
        ]);

        if ($request->hasFile('foto')) {
            // delete old file if stored on public disk
            if (!empty($item->foto) && str_starts_with($item->foto, '/storage/')) {
                $oldPath = ltrim(str_replace('/storage/', '', $item->foto), '/');
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('foto')->store('destinasi', 'public');
            $data['foto'] = '/storage/' . $path;
        }

        // parse CSV if provided to update rating/meta (only totalscore & reviewscount)
        if ($request->hasFile('csv_file')) {
            try {
                $file = $request->file('csv_file');
                $handle = fopen($file->getRealPath(), 'r');
                if ($handle !== false) {
                    $headers = null;
                    $colMap = [];
                    while (($row = fgetcsv($handle)) !== false) {
                        if (!$headers) {
                            $headers = $row;
                            foreach ($headers as $i => $h) {
                                $norm = strtolower(preg_replace('/[^a-z0-9]/', '', $h));
                                $colMap[$norm] = $i;
                            }

                            // detect flexible indices
                            $tsIdx = $this->detectColumnIndex($headers, ['total','score']);
                            $rcIdx = $this->detectColumnIndex($headers, ['review','count']);

                            // read the first data row after header
                            $dataRow = fgetcsv($handle);
                            if ($dataRow !== false && is_array($dataRow)) {
                                if ($tsIdx !== null && isset($dataRow[$tsIdx])) {
                                    $ts = $dataRow[$tsIdx] ?? null;
                                    if ($ts !== null) {
                                        $ts = trim($ts);
                                        $ts = str_replace(',', '.', $ts);
                                        if (is_numeric($ts)) {
                                            $data['rating'] = round(floatval($ts), 2);
                                        }
                                    }
                                }

                                if ($rcIdx !== null && isset($dataRow[$rcIdx])) {
                                    $rc = $dataRow[$rcIdx] ?? null;
                                    if ($rc !== null) {
                                        $rc = trim($rc);
                                        $rc = preg_replace('/[^0-9]/', '', $rc);
                                        if ($rc !== '') {
                                            $existingMeta = $item->meta ?? [];
                                            $data['meta'] = array_merge($existingMeta, ['reviews_count' => intval($rc)]);
                                        }
                                    }
                                }
                            }

                            break; // only first data row
                        }
                    }
                    fclose($handle);
                }
            } catch (\Exception $e) {
                // ignore CSV parse errors
            }
        }

        $item->update($data);

        return redirect()->route('admin.destinasi.index')->with('success', 'Destinasi diperbarui.');
    }

    public function destroy($id)
    {
        $item = Destinasi::findOrFail($id);
        if (!empty($item->foto) && str_starts_with($item->foto, '/storage/')) {
            $oldPath = ltrim(str_replace('/storage/', '', $item->foto), '/');
            Storage::disk('public')->delete($oldPath);
        }
        $item->delete();
        return redirect()->route('admin.destinasi.index')->with('success', 'Destinasi dihapus.');
    }
}
