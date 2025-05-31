<?php

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

if (!function_exists('fix_html_encoding')) {
    function fix_html_encoding($text)
    {
        $encodings = ['Windows-1252', 'ISO-8859-1', 'UTF-8'];
        foreach ($encodings as $enc) {
            if (mb_check_encoding($text, $enc)) {
                return mb_convert_encoding($text, 'UTF-8', $enc);
            }
        }
        // Fallback: tetap dikembalikan as-is
        return $text;
    }
}


if (!function_exists('fix_html_mojibake')) {
    function fix_html_mojibake(string $text): string
    {
        // Daftar penggantian umum karakter mojibake Windows-1252 ke UTF-8 asli
        $replacements = [
            'â€˜' => '‘', // left single quote
            'â€™' => '’', // right single quote / apostrof
            'â€œ' => '“', // left double quote
            'â€' => '”', // right double quote
            'â€¢' => '•', // bullet
            'â€“' => '–', // en dash
            'â€”' => '—', // em dash
            'â€¦' => '…', // ellipsis
            'â€' => '†', // dagger (kadang muncul terpotong)
            'â€¡' => '‡', // double dagger
            'â‚¬' => '€', // Euro
            'â„¢' => '™', // trademark
            'Â' => '',  // karakter tanda non-breaking space/encoding error (kadang muncul sendiri)
            "\xC2\xA0" => ' ', // non-breaking space (NBSP)
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }
}

if (!function_exists('html_to_text')) {
    function html_to_text(string $html): string
    {
        libxml_use_internal_errors(true);

        // $html = fix_html_encoding($html);

        // $html = fix_html_mojibake($html);

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
