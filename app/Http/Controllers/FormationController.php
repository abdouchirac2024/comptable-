<?php

namespace App\Http\Controllers;

use App\Services\FormationService;
use App\Http\Requests\Formation\StoreFormationRequest;
use App\Http\Requests\Formation\UpdateFormationRequest;
use App\Http\Resources\FormationResource;
use App\Models\Formation;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    protected $formationService;

    public function __construct(FormationService $formationService)
    {
        $this->formationService = $formationService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->query('search'),
        ];
        $perPage = $request->query('per_page', 15);
        $formations = $this->formationService->list($filters, $perPage);
        return FormationResource::collection($formations);
    }

    public function show($id)
    {
        $formation = $this->formationService->find($id);
        if (!$formation) {
            return response()->json(['message' => 'Formation non trouvée'], 404);
        }
        return new FormationResource($formation);
    }

    public function store(StoreFormationRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $formation = $this->formationService->create($data);
        return new FormationResource($formation);
    }

    public function update(UpdateFormationRequest $request, $id)
    {
        $formation = $this->formationService->find($id);
        if (!$formation) {
            return response()->json(['message' => 'Formation non trouvée'], 404);
        }
        \Log::info('UpdateFormationRequest all request data:', $request->all());
        $data = $request->validated();
        \Log::info('UpdateFormationRequest data:', $data);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $formation = $this->formationService->update($formation, $data);
        return new FormationResource($formation);
    }

    public function destroy($id)
    {
        $formation = $this->formationService->find($id);
        if (!$formation) {
            return response()->json(['message' => 'Formation non trouvée'], 404);
        }
        $this->formationService->delete($formation);
        return response()->json(['message' => 'Formation supprimée avec succès']);
    }
} 