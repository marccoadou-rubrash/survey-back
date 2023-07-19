<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CompanyDataTable;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index(CompanyDataTable $dataTable)
    {
        return $dataTable->render('admin.companies.index');
    }

    public function create(): Factory|\Illuminate\Foundation\Application|View|Application|RedirectResponse
    {
        try {

            return view('admin.companies.create');
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }

    public function save(Request $request): RedirectResponse
    {
        $company = Company::query();
        $data = $request->only(['name']);
        if ($request->file('logo')) {
            $extension = $request->file('logo')->getClientOriginalExtension();
            $name = $request->file('logo')->getClientOriginalName();
            $name = Str::slug(explode('.', $name)[0]) . '-' . time() . '.' . $extension;
            $path = 'uploads/companies/logo';
            $data['logo'] = $request->file('logo')->storePubliclyAs($path, $name);
        }


        $company->create($data);
        return to_route('admin.companies.list')->with(['success' => "Created"]);
    }
}
