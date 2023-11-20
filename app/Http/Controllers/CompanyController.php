<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function index()
    {

        $companies = Company::orderBy('id', 'desc')->paginate(10);
        $companiesResource = CompanyResource::collection($companies);

        return Inertia::render('Companies/Index', [
            'companies' => $companiesResource,
            'meta' => [
                'current_page' => $companies->currentPage(),
                'per_page' => $companies->perPage(),
                'total' => $companies->total(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Companies/Create');
    }


    public function store(CompanyStoreRequest $request)
    {
        $validatedData = $request->validated();

        $logoPath = $request->hasFile('logo')
            ? $request->file('logo')->store('logos', 'public')
            : null;
        $company = Company::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'logo' => $logoPath,
            'website' => $validatedData['website'],
        ]);

        return redirect()->route('companies.index')->with('success', 'Company created successfully!');
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return Inertia::render('Companies/Edit', [
            'company' => $company,
        ]);
    }

    public function update(CompanyUpdateRequest $request, Company $company)
    {

        $validatedData = $request->validated();
        if ($request->hasFile('logo')) {
            // Delete existing logo first
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            // Update the logo
            $logoPath = $request->file('logo')->store('logos', 'public');
            $company->logo = $logoPath;
        }

        // Update other fields
        $company->name = $validatedData['name'];
        $company->email = $validatedData['email'];
        $company->website = $validatedData['website'];

        // Save the changes
        $company->update();

        return redirect()->route('companies.index')->with('success', 'Company updated successfully!');
    }
    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);

        // Delete the company logo if it `exists`
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        try {
            $company->delete();
        } catch (\Exception $th) {
            
           return redirect()->route('companies.index')->with('error', 'Company cannot be deleted!');
        }

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully!');
    }
}
