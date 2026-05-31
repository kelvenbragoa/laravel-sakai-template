<?php

namespace App\Http\Controllers\Api\Mobile\CGatev2\ImageUpload;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        try {

            if (!$request->hasFile('image')) {
                return response()->json([
                    'error' => ['image' => ['File "image" não foi enviado']],
                    'message' => 'error',
                    'result' => [],
                ], 422);
            }

            $image = $request->file('image');

            if (!$image->isValid()) {
                return response()->json([
                    'error' => ['image' => ['Upload inválido (PHP error code: '.$image->getError().')']],
                    'message' => 'error',
                    'result' => [],
                ], 422);
            }

            $path = "uploads/cgatev2/" . now()->format('Y') . "/" . now()->format('m');

            $url = $image->store($path, 'public'); // storage/app/public/uploads/cgatev2/...
            // $storedPath = $image->store($path, 'network'); // returns path like uploads/cgatev2/2026/05/abcd1234.jpg
            // $fullPath = Storage::disk('network')->path($storedPath);

            return response()->json([
                'error' => [],
                'message' => 'success',
                'result' => [
                    ['image' => $url],
                ],
            ], 200);

        } catch (\Throwable $e) {
            Log::error('images.upload: exception', [
                'message' => $e->getMessage(),
                'class'   => get_class($e),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => ['server' => ['Erro interno ao fazer upload']],
                'message' => 'error',
                'result' => [],
            ], 500);
        }
    
}
}
