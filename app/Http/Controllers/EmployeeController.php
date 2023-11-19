<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Company;
use App\Models\Employee;
use App\Notifications\NewRegisterNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EmployeeController extends Controller
{

    public function index()
    {
        $employees = Employee::orderBy('id', 'desc')->paginate(10);
        $employeeResource = EmployeeResource::collection($employees);

        $companies = Company::all();

        return Inertia::render('Employees/Index', [
            'employees' => $employeeResource,
            'companies' => $companies,
            'meta' => [
                'current_page' => $employees->currentPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ],
        ]);
    }

    public function store(EmployeeRequest $request)
    {
        $validatedData = $request->validated();
        $employee = Employee::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'company_id' => $validatedData['company_id']
        ]);

        try {
            $employee->company->notify(new NewRegisterNotification($employee->company));
        } catch (\Exception $e) {
            Log::warning("cannot send email notification, mailer driver not set." . $e->getMessage());
        }

        return redirect()->route('employees.index')->with('success', 'Employee created successfully!');
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $validatedData = $request->validated();
        $employee->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'company_id' => $validatedData['company_id']
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
}
