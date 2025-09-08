<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Imports\ClientsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    //
    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        Client::destroy($ids);
        return redirect()->back()->with('success', 'Selected clients deleted successfully.');
    }
    public function toggleStatus(Request $request)
    {
        $clients= Client::findOrFail($request->id);
        $clients->status = $request->status;
        $clients->save();

        return response()->json(['success' => true]);
    }
    
    public function client(){
        $clients = Client::all();
        return view('client' ,compact('clients'));
    }
    public function addclient()
    {

        return view('add-client');
    }

    public function storeclient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cname' => 'required|min:3',
            'coname' => 'required|min:3',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'required|min:10',

        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $client = new Client();
        $client->cname = $request->cname;
        $client->coname = $request->coname;
        $client->email = $request->email;
        $client->phone = $request->phone;
        $client->address = $request->address;
        $client->save();

        return redirect()->route('client')->with('success', 'Client added successfully.');
    }

    public function deleteclient($id)
{
    $client = client::findOrFail($id);
    $client->delete();

    return redirect()->route('client')->with('success', 'client deleted successfully.');
}

public function viewclient($id)
{
    $client = client::with('services.vendor')->findOrFail($id); // Load client with services and vendor relationships
    return view('client-details', compact('client'));
}

public function editclient($id)
{
    $client = Client::findOrFail($id);
    return view('edit-client', compact('client'));
}

public function updateclient(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'cname' => 'required|min:3',
        'coname' => 'required|min:3',
        'email' => 'required|email|unique:clients,email,' . $id,
        'phone' => 'required|min:10',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $client = Client::findOrFail($id);
    $client->cname = $request->cname;
    $client->coname = $request->coname;
    $client->email = $request->email;
    $client->phone = $request->phone;
    $client->address = $request->address;
    $client->save();

    return redirect()->route('client')->with('success', 'Client updated successfully.');
}

/**
 * Handle bulk upload of clients from Excel file
 */
public function bulkUpload(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:2048'
    ]);

    try {
        $import = new ClientsImport;
        Excel::import($import, $request->file('file'));

        $failures = $import->failures();
        $errors = $import->errors();

        if ($failures->isNotEmpty() || $errors->isNotEmpty()) {
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }

            foreach ($errors as $error) {
                $errorMessages[] = $error;
            }

            return redirect()->route('client')->with('error', 'Import completed with errors: ' . implode(' | ', $errorMessages));
        }

        return redirect()->route('client')->with('success', 'Clients imported successfully!');
    } catch (\Exception $e) {
        return redirect()->route('client')->with('error', 'Import failed: ' . $e->getMessage());
    }
}

/**
 * Download sample Excel template for clients
 */
public function downloadTemplate()
{
    $headers = [
        'client_name',
        'company_name',
        'email',
        'phone',
        'address',
        'status'
    ];

    $sampleData = [
        [
            
        ],
       
    ];

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    foreach ($headers as $index => $header) {
        $sheet->setCellValue(chr(65 + $index) . '1', $header);
    }

    // Set sample data
    foreach ($sampleData as $rowIndex => $row) {
        foreach ($row as $colIndex => $value) {
            $sheet->setCellValue(chr(65 + $colIndex) . ($rowIndex + 2), $value);
        }
    }

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

    $fileName = 'clients_template.xlsx';
    $tempFile = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($tempFile);

    return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
}


}
