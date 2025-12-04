<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Service;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        $ids = array_map('intval', $ids);
        Service::whereNotNull('client_id')->whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', 'Selected Service deleted successfully.');
    }

    public function index(Request $request)
    {
        $query = Service::with(['client', 'vendor'])->whereNotNull('client_id');

        // Apply date range filtering
        if ($request->filled('from_date')) {
            $query->where('billing_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('billing_date', '<=', $request->to_date);
        }

        $services = $query->latest()->get();

        return view('services.index', compact('services'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $clients = Client::orderBy('cname')->get();
        $vendors = Vendor::orderBy('name')->get();
        $selectedClientId = $request->get('client_id');
        $selectedVendorId = $request->get('vendor_id');
        return view('services.create', compact('clients', 'vendors', 'selectedClientId', 'selectedVendorId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'services' => 'required|array|min:1',
            'services.*.vendor_id' => 'required|exists:vendors,id',
            'services.*.service_name' => 'required|string|max:255',
            'services.*.service_details' => 'nullable|string',
            'services.*.start_date' => 'required|date',
            'services.*.end_date' => 'required|date|after_or_equal:services.*.start_date',
            'services.*.billing_date' => 'required|date',
            'services.*.status' => 'required|in:active,inactive,expired,pending',
        ], [
            'client_id.required' => 'Please select a client.',
            'client_id.exists' => 'Selected client does not exist.',
            'services.required' => 'At least one service is required.',
            'services.*.vendor_id.required' => 'Please select a vendor for each service.',
            'services.*.vendor_id.exists' => 'Selected vendor does not exist.',
            'services.*.service_name.required' => 'Service name is required.',
            'services.*.start_date.required' => 'Start date is required.',
            'services.*.end_date.required' => 'End date is required.',
            'services.*.end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'services.*.billing_date.required' => 'Billing date is required.',
            'services.*.billing_date.date' => 'Billing date must be a valid date.',
            'services.*.status.required' => 'Status is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create multiple services
        foreach ($request->services as $serviceData) {
            Service::create([
                'client_id' => $request->client_id,
                'vendor_id' => $serviceData['vendor_id'],
                'service_name' => $serviceData['service_name'],
                'service_details' => $serviceData['service_details'] ?? null,
                'start_date' => $serviceData['start_date'],
                'end_date' => $serviceData['end_date'],
                'billing_date' => $serviceData['billing_date'],
                'status' => $serviceData['status'],
            ]);
        }

        return redirect()->route('services.index')
            ->with('success', 'Services created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::with(['client', 'vendor'])->whereNotNull('client_id')->findOrFail($id);
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = Service::whereNotNull('client_id')->findOrFail($id);
        $clients = Client::orderBy('cname')->get();
        $vendors = Vendor::orderBy('name')->get();
        return view('services.edit', compact('service', 'clients', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $service = Service::whereNotNull('client_id')->findOrFail($id);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'vendor_id' => 'required|exists:vendors,id',
            'service_name' => 'required|string|max:255',
            'service_details' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'billing_date' => 'required|date',
            'status' => 'required|in:active,inactive,expired,pending',
        ], [
            'client_id.required' => 'Please select a client.',
            'vendor_id.required' => 'Please select a vendor.',
            'service_name.required' => 'Service name is required.',
            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'billing_date.required' => 'Billing date is required.',
            'billing_date.date' => 'Billing date must be a valid date.',
            'status.required' => 'Status is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update the service
        $service->update([
            'client_id' => $request->client_id,
            'vendor_id' => $request->vendor_id,
            'service_name' => $request->service_name,
            'service_details' => $request->service_details,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'billing_date' => $request->billing_date,
            'status' => $request->status,
        ]);

        return redirect()->route('services.index')
            ->with('success', 'Service updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::whereNotNull('client_id')->findOrFail($id);
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service deleted successfully!');
    }
}
