<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ApiService;

class PhmetroController extends Controller {
    protected $apiService;

    public function __construct(ApiService $apiService) {
        $this->apiService = $apiService;
    }

    public function index() {
        $data = $this->apiService->listarPhmetroEmJson();
        return view('phmetro.index');
    }

    public function recarregar() {
        $data = $this->apiService->listarPhmetroEmJson();
        return response()->json($data);
    }
}