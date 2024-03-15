<?php

namespace App\Services;

class ParsonsProblemService
{
    public function convertAnswerPpTwoDimension($data) {
        // Pisahkan per baris
        $lines = explode("\n", $data);

        foreach ($lines as &$line) {
            // Identifikasi array dengan kurung siku dan ambil nilai di dalamnya
            if (preg_match_all('/\[([^\]]+)\]/', $line, $matches)) {
                foreach ($matches[1] as $match) {
                    $arrayValues = array_map('trim', explode(',', $match));

                    // Ambil elemen ke-0 dari array
                    $firstValue = reset($arrayValues);

                    // Gantikan array dengan elemen ke-0
                    $line = str_replace("[$match]", $firstValue, $line);
                }
            }
        }

        // Gabungkan kembali baris-baris yang telah diubah
        $dataConvert = implode("\n", $lines);

        return $dataConvert;
    }

    public function randomizeArrayOrder($array) {
        $values = array_values($array);
        shuffle($values);
    
        $result = [];
        foreach ($array as $key => $originalKey) {
            $result[$originalKey] = $values[$key];
        }
    
        return $result;
    }

    function encodeJsParsosnPpTwoDimension($code) {
        // Temukan semua array dalam $code
        preg_match_all('/\[([^\]]+)\]/', $code, $matches);
    
        // Jika tidak ada array yang ditemukan, kembalikan $code as is
        if (empty($matches[1])) {
            return $code;
        }
        
        $tes = $this->randomizeArrayOrder($matches[1]);

        // Loop melalui setiap array yang ditemukan dan konversi ke format yang diinginkan
        foreach ($matches[1] as $match) {
            // Pisahkan elemen array
            $elements = explode(',', $match);
            $elements = $this->randomizeArrayOrder($elements);
            // Bentuk string yang diinginkan
            $replace = '$$toggle::' . implode('::', $elements) . '$$';
    
            // Ganti array dalam $code dengan string yang dibentuk
            $code = preg_replace('/\[' . preg_quote($match, '/') . '\]/', $replace, $code);
        }
    
        return $code;
    }

} 