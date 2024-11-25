<?php
namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use Gemini\Data\Blob;
use Gemini\Enums\MineType;
use Gemini\Data\Model;
use Gemini\Enums\MimeType;

class GeminiService{
    public function analyzeImage(string $imageUrl){
        $promt = "";
        $imageBlog = new Blog(
            mimeType: MimeType::IMAGE_JPEG,
            data: base64_encode(file_get_contents($imageUrl))
        );
        $result = Gemini::geminiProvision()->generateContent([$promt, $imageBlog]);
    }
}