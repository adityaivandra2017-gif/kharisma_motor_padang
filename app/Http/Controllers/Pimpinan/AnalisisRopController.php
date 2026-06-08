<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Services\Pimpinan\AnalisisRopService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalisisRopController extends Controller
{
    public function __construct(
        private readonly AnalisisRopService $analisisService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $this->analisisService->resolveFilters(
            $request->string('search')->toString(),
            $request->string('status')->toString(),
        );

        $summaryItems = $this->analisisService->allForSummary(
            $filters['search'],
            $filters['status'],
        );

        return view('pimpinan.analisis-persediaan.analisis-rop', [
            'analisis' => $this->analisisService->paginate(
                $filters['search'],
                $filters['status'],
            ),
            'search' => $filters['search'],
            'status' => $filters['status'],
            'ringkasan' => $this->analisisService->summarize($summaryItems),
            'prioritasRestock' => $this->analisisService->priorityRestock($filters['search']),
            'analisisService' => $this->analisisService,
        ]);
    }
}
