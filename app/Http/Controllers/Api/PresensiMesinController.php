<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SolutionCloudService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PresensiMesinController extends Controller
{
    protected SolutionCloudService $solutionCloud;

    public function __construct(SolutionCloudService $solutionCloud)
    {
        $this->solutionCloud = $solutionCloud;
    }

    /**
     * Ambil semua data presensi dari mesin Solution Cloud.
     *
     * @param  Request  $request
     * @return JsonResponse
     *
     * @bodyParam nomor_mesin string required Serial Number mesin absensi. Example: BWXP233560696
     * @bodyParam password string required Password cloud server. Example: solution
     */
    public function fetchAll(Request $request): JsonResponse
    {
        $request->validate([
            'nomor_mesin' => 'required|string',
            'password'    => 'required|string',
        ]);

        $result = $this->solutionCloud->fetchPresensi(
            $request->input('nomor_mesin'),
            $request->input('password')
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Ambil data presensi berdasarkan rentang index.
     *
     * @param  Request  $request
     * @return JsonResponse
     *
     * @bodyParam nomor_mesin string required Serial Number mesin absensi. Example: BWXP233560696
     * @bodyParam password string required Password cloud server. Example: solution
     * @bodyParam start_index int Index awal (default: 0). Example: 0
     * @bodyParam end_index int Index akhir inclusive (default: 9). Example: 2
     */
    public function fetchByIndex(Request $request): JsonResponse
    {
        $request->validate([
            'nomor_mesin' => 'required|string',
            'password'    => 'required|string',
            'start_index' => 'nullable|integer|min:0',
            'end_index'   => 'nullable|integer|min:0',
        ]);

        $startIndex = (int) $request->input('start_index', 0);
        $endIndex   = (int) $request->input('end_index', 9);

        if ($startIndex > $endIndex) {
            return response()->json([
                'success' => false,
                'message' => 'start_index tidak boleh lebih besar dari end_index.',
            ], 422);
        }

        $result = $this->solutionCloud->fetchPresensiByIndex(
            $request->input('nomor_mesin'),
            $request->input('password'),
            $startIndex,
            $endIndex
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}
