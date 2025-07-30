<?php

namespace App\Http\Controllers;

use App\Services\PartenaireService;
use App\Http\Requests\Partenaire\StorePartenaireRequest;
use App\Http\Requests\Partenaire\UpdatePartenaireRequest;
use App\Http\Resources\PartenaireResource;
use App\Models\Partenaire;
use Illuminate\Http\Request;

class PartenaireController extends Controller
{
    protected $partenaireService;

    public function __construct(PartenaireService $partenaireService)
    {
        $this->partenaireService = $partenaireService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->query('search'),
        ];
        $perPage = $request->query('per_page', 15);
        $partenaires = $this->partenaireService->list($filters, $perPage);
        return PartenaireResource::collection($partenaires);
    }

    public function show($id)
    {
        $partenaire = $this->partenaireService->find($id);
        if (!$partenaire) {
            return response()->json(['message' => 'Partenaire non trouvé'], 404);
        }
        return new PartenaireResource($partenaire);
    }

    public function edit(Request $request, $id)
    {
        $partenaire = $this->partenaireService->find($id);
        if (!$partenaire) {
            return response()->json(['message' => 'Partenaire non trouvé'], 404);
        }
        
        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        
        $partenaire = $this->partenaireService->update($partenaire, $data);
        return new PartenaireResource($partenaire);
    }

    public function store(StorePartenaireRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $partenaire = $this->partenaireService->create($data);
        return new PartenaireResource($partenaire);
    }

    public function update(UpdatePartenaireRequest $request, $id)
    {
        $partenaire = $this->partenaireService->find($id);
        if (!$partenaire) {
            return response()->json(['message' => 'Partenaire non trouvé'], 404);
        }
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $partenaire = $this->partenaireService->update($partenaire, $data);
        return new PartenaireResource($partenaire);
    }

    public function destroy($id)
    {
        $partenaire = $this->partenaireService->find($id);
        if (!$partenaire) {
            return response()->json(['message' => 'Partenaire non trouvé'], 404);
        }
        $this->partenaireService->delete($partenaire);
        return response()->json(['message' => 'Partenaire supprimé avec succès']);
    }
} 