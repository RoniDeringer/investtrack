<?php

namespace App\Http\Controllers;

use App\Services\PortfolioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function show(Request $request, PortfolioService $portfolio): JsonResponse
    {
        $data = $portfolio->buildExamplePortfolio();

        return response()->json($data);
    }
}

