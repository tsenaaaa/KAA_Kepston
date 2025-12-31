<?php
if ($argc < 2) {
    echo "Usage: php debug_csv.php path/to/file.csv\n";
    exit(1);
}
$path = $argv[1];
if (!file_exists($path)) { echo "File not found: $path\n"; exit(2); }
if (($h = fopen($path, 'r')) === false) { echo "Cannot open file\n"; exit(3); }
$header = fgetcsv($h);
echo "Detected headers:\n";
print_r($header);
$norm = array_map(function($h){ return strtolower(preg_replace('/[^a-z0-9]+/i','',$h)); }, $header);
echo "Normalized headers:\n";
print_r($norm);
$rows = 0;
echo "Parsed rows (first 10):\n";
while (($row = fgetcsv($h)) !== false && $rows < 10) {
    $rows++;
    // find indices
    $idxScore = null; $idxReviews = null;
    foreach ($norm as $i => $k) {
        if ($idxScore === null && (strpos($k,'totalscore')!==false || strpos($k,'score')!==false || strpos($k,'rating')!==false)) $idxScore = $i;
        if ($idxReviews === null && (strpos($k,'reviewscount')!==false || strpos($k,'reviews')!==false || strpos($k,'count')!==false)) $idxReviews = $i;
    }
    $rawScore = $idxScore !== null && isset($row[$idxScore]) ? $row[$idxScore] : '';
    $rawReviews = $idxReviews !== null && isset($row[$idxReviews]) ? $row[$idxReviews] : '';
    $score = $rawScore === '' ? null : round((float)str_replace(',', '.', preg_replace('/[^0-9,\.]/','',$rawScore)),2);
    $reviews = $rawReviews === '' ? 0 : (int)preg_replace('/[^0-9\-]/','',$rawReviews);
    echo "Row {$rows}: title=" . ($row[0] ?? '') . " | rawScore='{$rawScore}' -> rating={$score} | rawReviews='{$rawReviews}' -> reviews_count={$reviews}\n";
}
fclose($h);