<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\CompanyStoreRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Http\Resources\Company\CompanyResource;
use App\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Company Managment
 *
 * @authenticated
 *
 * API's for company resource managment
 *
 */
class CompanyController extends Controller
{
    /**
     * @unauthenticated
     *
     * Returns list of companies
     *
     * Display paginated(by 20) listing of companies.<br>
     *
     * @queryParam page integer Non required field
     *
     */
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $limit = $request->get('limit', 20);
        return CompanyResource::collection(Company::paginate($limit));
    }

    /**
     * @unauthenticated
     *
     * Returns single company
     *
     * Display company specified by id
     *
     * @urlParam companyId integer required Id of the company in database
     */
    public function show(Company $company): CompanyResource
    {
        return new CompanyResource($company);
    }

    /**
     * Store single company
     *
     * Access Level needed: 2<br>
     *
     * Adds company to database
     *
     * @bodyParam name string required Company's name. Example: Stettiner
     * @bodyParam nip string required Company's NIP. Example: 1060000062
     * @bodyParam address string required Company's address. Example: 10 Wolfgang Street
     * @bodyParam city string required Company's city Example: Szczecin
     *
     */
    public function store(CompanyStoreRequest $request): JsonResponse
    {
        $company = Company::create($request->validated());

        $data = [
            "message" => "Pomyślnie dodano firmę",
            "id" => $company->id,
        ];

        return response()->json($data);
    }

    /**
     * Update single company
     *
     * Access Level needed: 2<br>
     *
     * Updates company's columns find by id
     *
     * @urlParam company integer required Id of company in database
     *
     * @bodyParam name string required Company's name. Example: Stettiner
     * @bodyParam nip string required Company's NIP. Example: 1060000062
     * @bodyParam address string required Company's address. Example: 10 Wolfgang Street
     * @bodyParam city string required Company's city Example: Szczecin
     *
     */
    public function update(CompanyUpdateRequest $request, Company $company)
    {
        $company->update($request->validated());

        return response()->json([
            'message' => "Pomyślnie zaktualizowano firmę",
            'data' => $company->fresh()
        ]);
    }

    /**
     * Delete single company
     *
     * Access Level needed: 2<br>
     *
     * Remove specified reward from database.
     * May throw ModelNotFoundException if reward is not in database.
     *
     */
    public function destroy(Company $company): JsonResponse
    {
        try {
            $company->delete();
        } catch (ModelNotFoundException $exception) {
            info($exception);
        }
        return response()->json([
            "message" => "Pomyślnie usunięto firmę"
        ]);
    }
}
