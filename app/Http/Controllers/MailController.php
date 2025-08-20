<?php

namespace App\Http\Controllers;

use App\Mail\RenewalMail;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailController extends Controller
{
    /**
     * Show the send mail form for a specific service
     *
     * @param int $service_id
     * @return \Illuminate\Http\Response
     */
    public function sendMailForm($service_id)
    {
        $service = Service::with(['client', 'vendor'])->findOrFail($service_id);

        // Pre-fill subject with service information
        $defaultSubject = "Service Renewal Reminder - {$service->service_name}";

        return view('send-mail', compact('service', 'defaultSubject'));
    }

    /**
     * Send the renewal email
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'to_email' => 'required|email',
            'cc_emails' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $service = Service::with(['client', 'vendor'])->findOrFail($request->service_id);

            // Parse CC emails
            $ccEmails = [];
            if ($request->cc_emails) {
                $ccEmails = array_filter(array_map('trim', explode(',', $request->cc_emails)));

                // Validate each CC email
                foreach ($ccEmails as $email) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        return redirect()->back()
                            ->withErrors(['cc_emails' => "Invalid email address: {$email}"])
                            ->withInput();
                    }
                }
            }

            // Send the email
            Mail::to($request->to_email)
                ->cc($ccEmails)
                ->send(new RenewalMail($service, $request->subject, $request->message));

            return redirect()->back()->with('success', 'Renewal email has been sent successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to send email. Please try again.'])
                ->withInput();
        }
    }
}
