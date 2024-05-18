<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileService;
use App\Models\DocumentosClientes;
use App\Models\Pagos;
use Illuminate\Support\Facades\Log;

class FilesController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'urls' => 'required|array',
            ]);

            $urls = $request->input('urls');
            Log::info("URLS", $urls);

            foreach ($urls as $url) {
                $path = parse_url($url, PHP_URL_PATH);
                Log::info("PATHS " . $path);

                $basePath = explode('/', trim($path, '/'))[0];
                $id = explode('/', trim($path, '/'))[1];

                Log::info("basePath " . $basePath);
                Log::info("id " . $id);

                if ($basePath === 'Cliente') {
                    // Delete file from S3
                    $this->fileService->deleteFile($path);

                    // Delete the record from DocumentosClientes
                    DocumentosClientes::where('cliente_id', $id)
                        ->where('url', $url)
                        ->delete();
                } elseif ($basePath === 'Pagos') {
                    // Delete file from S3
                    $this->fileService->deleteFile($path);

                    // Update the comprobante field to null in Pagos
                    Pagos::where('id', $id)
                        ->where('comprobante', $url)
                        ->update(['comprobante' => null]);
                } else {
                    Log::warning("Unsupported path type: $basePath");
                }
            }

            return response()->json(['message' => 'Files processed successfully.'], 200);
        } catch (\Exception $e) {
            Log::error("Failed to delete file: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
