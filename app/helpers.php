<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

if (!function_exists('auth_role')) {
    function auth_role(): ?string
    {
        $user = Auth::user();
        return $user?->roleValue();
    }
}

if (!function_exists('array_to_object')) {
    function array_to_object(mixed $data): mixed
    {
        if (is_array($data)) {
            return (object) array_map('array_to_object', $data);
        }
        return $data;
    }
}

if (!function_exists('format_month_year')) {
    function format_month_year(int $month, int $year, string $format = 'M Y'): string
    {
        // Set locale to Indonesian
        Carbon::setLocale(env('APP_LOCALE', 'id'));
        // Create Carbon date object
        $date = Carbon::createFromDate($year, $month, 1);
        // Format as "Jun 2025"
        return $date->translatedFormat($format);
    }

}

if (!function_exists('format_date')) {
    /**
     * Format tanggal dengan locale sesuai APP_LOCALE (default: id).
     *
     * @param string|\DateTimeInterface $date Tanggal dalam format string atau objek DateTime
     * @param string $format Format output, default 'd M Y'
     * @return string
     */
    function format_date($date, string $format = 'd M Y'): string
    {
        // Set locale ke Indonesian (atau sesuai APP_LOCALE)
        Carbon::setLocale(env('APP_LOCALE', 'id'));

        // Buat objek Carbon dari input
        $carbon = $date instanceof \DateTimeInterface
            ? Carbon::instance($date)
            : Carbon::parse($date);

        // Format tanggal dengan translatedFormat
        return $carbon->translatedFormat($format);
    }
}


if (!function_exists('parse_to_float')) {
    function parse_to_float($number) {
        $number = trim($number);

        // Jika ada koma dan titik, tentukan mana yang desimal
        if (preg_match('/[.,]\d{1,2}$/', $number, $matches)) {
            // Ambil pemisah desimal terakhir
            $decimal_separator = $matches[0][0];
            // Hilangkan semua selain angka dan pemisah desimal terakhir
            $number = preg_replace('/[.,](?=.*[.,]\d{1,2}$)/', '', $number);
            // Ganti desimal ke titik
            $number = str_replace($decimal_separator, '.', $number);
        } else {
            // Tidak ada desimal, hilangkan semua koma/titik
            $number = str_replace([',', '.'], '', $number);
        }

        return (float) $number;
    }
}


if (!function_exists('html_to_text')) {
    function html_to_text(string $html): string
    {
        libxml_use_internal_errors(true);

        // Ganti <br> dengan newline (agar tidak hilang saat di-parse)
        $html = preg_replace('/<br\s*\/?>/i', "\n", $html);

        // Inisialisasi DOMDocument
        $dom = new DOMDocument();
        $dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
        $body = $dom->getElementsByTagName('body')->item(0);

        // Jika <body> tidak ada, pakai seluruh dokumen
        $root = $body ?? $dom->documentElement;

        // Fungsi rekursif untuk ekstrak teks + struktur
        $extractNodeText = function ($node) use (&$extractNodeText): string {
            $text = '';

            foreach ($node->childNodes as $child) {
                if ($child->nodeType === XML_TEXT_NODE) {
                    $text .= $child->nodeValue;
                } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                    // Tangani <br> (harusnya sudah jadi "\n", tapi jaga-jaga)
                    if (strtolower($child->nodeName) === 'br') {
                        $text .= "\n";
                        continue;
                    }

                    // Proses konten anak dan tambahkan \n\n (simulasi Shift+Enter)
                    $childText = $extractNodeText($child);
                    if (trim($childText) !== '') {
                        $text .= $childText . "\n\n";
                    }
                }
            }

            return $text;
        };

        $rawText = $extractNodeText($root);

        // Bersihkan:
        // - Ganti \xC2\xA0 (non-breaking space) jadi spasi biasa
        // - Hapus spasi berlebih di awal/akhir baris
        // - Hapus baris kosong ganda
        $cleanText = str_replace("\xC2\xA0", ' ', $rawText);
        $lines = array_map('trim', explode("\n", $cleanText));
        $lines = array_filter($lines, fn($line) => $line !== '');
        $finalText = implode("\n", $lines);

        return $finalText;
    }
}

if (!function_exists('extract_by_template_old')) {
    function extract_by_template_old(string $template, string $html, bool $toText = false): array
    {
        $text = $html;

        if ($toText) {
            // Ubah HTML ke plain text menggunakan fungsi kamu
            $text = html_to_text($html);
        }

        // Split template menjadi bagian-bagian statis dan placeholder
        preg_match_all('/\[\[(.*?)\]\]/', $template, $paramMatches);
        $fields = $paramMatches[1];

        $parts = preg_split('/\[\[(.*?)\]\]/', $template);

        $results = [];
        $offset = 0;

        for ($i = 0; $i < count($fields); $i++) {
            $start = $parts[$i];
            $end = $parts[$i + 1];

            // Cari posisi start
            $startPos = strpos($text, $start, $offset);
            if ($startPos === false)
                break;
            $startPos += strlen($start);

            // Cari posisi end setelah start
            $endPos = $end !== '' ? strpos($text, $end, $startPos) : strlen($text);
            if ($endPos === false)
                break;

            $value = substr($text, $startPos, $endPos - $startPos);
            $results[$fields[$i]] = trim($value);

            // Geser offset untuk pencarian berikutnya
            $offset = $endPos;
        }

        return $results;
    }
}

if (!function_exists('extract_by_template')) {
    /**
     * Ekstrak field dari email text berdasarkan template dengan placeholder [[FIELD]]
     *
     * @param string $template
     * @param string $emailText
     * @return array
     */
    function extract_by_template(string $template, string $emailText, bool $toText = false): array
    {
        // Normalisasi newline: jika user pakai "\n" secara literal
        $template = str_replace('\n', "\n", $template);
        
        if ($toText) {
            // Ubah HTML ke plain text menggunakan fungsi kamu
            $emailText = html_to_text($emailText);
        }
        $emailText = str_replace('\n', "\n", $emailText);

        // Escape regex pattern
        $pattern = preg_quote($template, '/');

        // Temukan semua [[FIELD]] dari template
        preg_match_all('/\\[\\[([A-Z0-9_]+)\\]\\]/', $template, $matches);
        $fields = $matches[1];

        // Ganti [[FIELD]] jadi regex named group
        foreach ($fields as $field) {
            $pattern = str_replace('\[\[' . $field . '\]\]', '(?P<' . $field . '>.*?)', $pattern);
        }

        // Tambahkan flag /s agar dot (.) cocok dengan newline
        $pattern = '/^' . $pattern . '$/s';

        if (preg_match($pattern, $emailText, $result)) {
            return array_filter(
                $result,
                fn($key) => in_array($key, $fields),
                ARRAY_FILTER_USE_KEY
            );
        }

        return []; // Tidak match
    }
}
