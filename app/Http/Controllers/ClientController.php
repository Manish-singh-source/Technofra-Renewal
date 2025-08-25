<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    //


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


}
