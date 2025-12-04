<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorServiceController extends Controller
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
        VendorService::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', 'Selected Vendor Service deleted successfully.');
    }

    public function index(Request $request)
    {
        $query = VendorService::with('vendor');

        // Apply date range filtering
        if ($request->filled('from_date')) {
            $query->where('billing_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('billing_date', '<=', $request->to_date);
        }

        $services = $query->latest()->get();

        return view('vendor-services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $vendors = Vendor::orderBy('name')->get();
        $selectedVendorId = $request->get('vendor_id');
        return view('vendor-services.create', compact('vendors', 'selectedVendorId'));
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
            'vendor_id' => 'required|exists:vendors,id',
            'services' => 'required|array|min:1',
            'services.*.service_name' => 'required|string|max:255',
            'services.*.service_details' => 'nullable|string',
            'services.*.plan_type' => 'required|in:monthly,yearly,quarterly',
            'services.*.start_date' => 'required|date',
            'services.*.end_date' => 'required|date|after_or_equal:services.*.start_date',
            'services.*.billing_date' => 'nullable|date',
            'services.*.status' => 'required|in:active,inactive,expired,pending',
        ], [
            'vendor_id.required' => 'Please select a vendor.',
            'vendor_id.exists' => 'Selected vendor does not exist.',
            'services.required' => 'At least one service is required.',
            'services.*.service_name.required' => 'Service name is required.',
            'services.*.plan_type.required' => 'Plan type is required.',
            'services.*.plan_type.in' => 'Invalid plan type.',
            'services.*.start_date.required' => 'Start date is required.',
            'services.*.end_date.required' => 'End date is required.',
            'services.*.end_date.after_or_equal' => 'End date must be after or equal to start date.',
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
            $data = [
                'vendor_id' => $request->vendor_id,
                'service_name' => $serviceData['service_name'],
                'service_details' => $serviceData['service_details'] ?? null,
                'plan_type' => $serviceData['plan_type'],
                'start_date' => $serviceData['start_date'],
                'end_date' => $serviceData['end_date'],
                'status' => $serviceData['status'],
            ];
            if (!empty($serviceData['billing_date'])) {
                $data['billing_date'] = $serviceData['billing_date'];
            }
            VendorService::create($data);
        }

        return redirect()->route('vendor-services.index')
            ->with('success', 'Vendor Services created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = VendorService::with('vendor')->findOrFail($id);
        return view('vendor-services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = VendorService::findOrFail($id);
        $vendors = Vendor::orderBy('name')->get();
        return view('vendor-services.edit', compact('service', 'vendors'));
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
        $service = VendorService::findOrFail($id);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'service_name' => 'required|string|max:255',
            'service_details' => 'nullable|string',
            'plan_type' => 'required|in:monthly,yearly,quarterly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'billing_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,expired,pending',
        ], [
            'vendor_id.required' => 'Please select a vendor.',
            'service_name.required' => 'Service name is required.',
            'plan_type.required' => 'Plan type is required.',
            'plan_type.in' => 'Invalid plan type.',
            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'billing_date.date' => 'Billing date must be a valid date.',
            'status.required' => 'Status is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update the service
        $data = [
            'vendor_id' => $request->vendor_id,
            'service_name' => $request->service_name,
            'service_details' => $request->service_details,
            'plan_type' => $request->plan_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ];
        if (!empty($request->billing_date)) {
            $data['billing_date'] = $request->billing_date;
        } else {
            $data['billing_date'] = null;
        }
        $service->update($data);

        return redirect()->route('vendor-services.index')
            ->with('success', 'Vendor Service updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = VendorService::findOrFail($id);
        $service->delete();

        return redirect()->route('vendor-services.index')
            ->with('success', 'Vendor Service deleted successfully!');
    }
}
