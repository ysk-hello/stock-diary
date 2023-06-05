<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xls as XlsReader;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('company');
    }

    public function uploadFile(Request $request)
    {
        try {
            $file = $request->file('file');

            if(isset($file)){
                $reader = new XlsReader();
                $spreadsheet = $reader->load($file->path());
                $sheet = $spreadsheet->getActiveSheet();
                
                DB::transaction(function () use ($sheet) {
                    foreach ($sheet->getRowIterator() as $row) {
                        if ($row->getRowIndex() === 1) {
                            continue;
                        }
            
                        $code = $sheet->getCell('B' . $row->getRowIndex())->getValue();
                        $name = $sheet->getCell('C' . $row->getRowIndex())->getValue();

                        Company::updateOrCreate(
                            ['code' => $code],
                            ['name' => $name]
                        );
                    }
                });
            }
        } catch(Exception $e) {
            logger()->error($e);
            throw $e;
        }
    }

    public function getCompanies(Request $request)
    {
        return Company::orderBy('code', 'asc')->get();
    }

    public function searchCompanies(Request $request)
    {
        $keyword = '%' . trim($request['keyword']) . '%';

        $companies = Company::where('companies.code', 'like', $keyword)
            ->orWhere('companies.name', 'like', $keyword);
        
        $companies = $companies->orderBy('companies.code', 'asc');

        return $companies->paginate(50);
    }

    public function getName(Request $request)
    {
        return Company::where('code', '=', $request['code'])->first();
    }
}
