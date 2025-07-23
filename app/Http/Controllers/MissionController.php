<?php

namespace App\Http\Controllers;

use App\Services\MissionService;
use App\Http\Requests\Mission\StoreMissionRequest;
use App\Http\Requests\Mission\UpdateMissionRequest;
use App\Http\Resources\MissionResource;
use App\Models\Mission;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    protected $missionService;

    public function __construct(MissionService $missionService)
    {
        $this->missionService = $missionService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->query('search'),
        ];
        $perPage = $request->query('per_page', 15);
        $missions = $this->missionService->list($filters, $perPage);
        return MissionResource::collection($missions);
    }

    public function show($id)
    {
        $mission = $this->missionService->find($id);
        if (!$mission) {
            return response()->json(['message' => 'Mission non trouvée'], 404);
        }
        return new MissionResource($mission);
    }

    public function store(StoreMissionRequest $request)
    {
        \Log::info('MissionController store - all request data:', $request->all());
        $data = $request->validated();
        \Log::info('MissionController store - validated data:', $data);
        $mission = $this->missionService->create($request->validated());
        return new MissionResource($mission);
    }

    public function update(UpdateMissionRequest $request, $id)
    {
        $mission = $this->missionService->find($id);
        if (!$mission) {
            return response()->json(['message' => 'Mission non trouvée'], 404);
        }
        $mission = $this->missionService->update($mission, $request->validated());
        return new MissionResource($mission);
    }

    public function destroy($id)
    {
        $mission = $this->missionService->find($id);
        if (!$mission) {
            return response()->json(['message' => 'Mission non trouvée'], 404);
        }
        $this->missionService->delete($mission);
        return response()->json(['message' => 'Mission supprimée avec succès']);
    }
} 