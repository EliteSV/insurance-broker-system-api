<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Services\FileService;
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Cliente::with("documentos")->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|max:255',
                'dui' => 'required|max:255|unique:clientes,dui',
                'nit' => 'required|max:255|unique:clientes,nit',
                'email' => 'required|email|max:255|unique:clientes,email',
                'direccion' => 'required|max:255',
                'telefono' => 'required|max:255',
                'documentos.*.tipo_documento_id' => 'required_with:documentos.*.file|exists:tipos_documentos,id',
            ]);

            $cliente = Cliente::create($request->all());
            $documentos = [];

            if ($request->has('documentos')) {
                foreach ($request->documentos as $index => $doc) {
                    $doc['file'] = $request->file("documentos.$index.file");
                    $documentos[] = $doc;
                }
                $this->handleDocumentUploads($cliente, $documentos);
            }

            return response()->json($cliente);
        } catch (\Exception $e) {
            Log::error('Failed to store Cliente: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Cliente::with('documentos')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            info($request->all());
            $request->validate([
                'nombre' => 'required|max:255',
                'dui' => 'required|max:255',
                'nit' => 'required|max:255',
                'email' => 'required|email|max:255',
                'telefono' => 'required|max:255',
                'direccion' => 'required|max:255',
                'documentos.*.tipo_documento_id' => 'required_with:documentos.*.file|exists:tipos_documentos,id',
            ]);

            $cliente = Cliente::findOrFail($id);

            $cliente->update($request->all());
            $documentos = [];

            if ($request->has('documentos')) {
                foreach ($request->documentos as $index => $doc) {
                    $doc['file'] = $request->file("documentos.$index.file");
                    $documentos[] = $doc;
                }
                $this->handleDocumentUploads($cliente, $documentos);
            }

            return response()->json($cliente);
        } catch (\Exception $e) {
            Log::error('Failed to update Cliente: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Cliente::destroy($id);
    }

    private function handleDocumentUploads($cliente, $documentos)
    {
        foreach ($documentos as $doc) {
            if (isset($doc['file']) && $doc['file']->isValid()) {
                $path = "Cliente/{$cliente->id}";
                try {
                    $url = $this->fileService->uploadFile($doc['file'], $path);
                    $cliente->documentos()->create([
                        'url' => $url,
                        'tipo_documento_id' => $doc['tipo_documento_id'],
                    ]);
                    Log::info("Document created: URL {$url}");
                } catch (\Exception $e) {
                    Log::error("Failed to upload file or create document: " . $e->getMessage());
                }
            } else {
                Log::error("Invalid or no file provided for tipo_documento_id: {$doc['tipo_documento_id']}");
            }
        }
    }
}
