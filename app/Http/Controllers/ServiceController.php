<?php

namespace App\Http\Controllers;

use App\Services\ServiceService;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->query('search'),
        ];
        $perPage = $request->query('per_page', 15);
        $services = $this->serviceService->list($filters, $perPage);
        return ServiceResource::collection($services);
    }

    public function show($id)
    {
        $service = $this->serviceService->find($id);
        if (!$service) {
            return response()->json(['message' => 'Service non trouvé'], 404);
        }
        return new ServiceResource($service);
    }

    public function store(StoreServiceRequest $request)
    {
        \Log::info('All request data:', $request->all());
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $service = $this->serviceService->create($data);
        return new ServiceResource($service);
    }

    public function update(UpdateServiceRequest $request, $id)
    {
        $service = $this->serviceService->find($id);
        if (!$service) {
            return response()->json(['message' => 'Service non trouvé'], 404);
        }
        \Log::info('All request data:', $request->all());
        $data = $request->validated();
        \Log::info('UpdateServiceRequest data:', $data);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }
        $service = $this->serviceService->update($service, $data);
        return new ServiceResource($service);
    }

    public function destroy($id)
    {
        $service = $this->serviceService->find($id);
        if (!$service) {
            return response()->json(['message' => 'Service non trouvé'], 404);
        }
        $this->serviceService->delete($service);
        return response()->json(['message' => 'Service supprimé avec succès']);
    }
} 