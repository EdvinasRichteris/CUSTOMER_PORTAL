<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Comment;
use App\Models\Load;

class InvoiceController extends Controller
{

    public function index()
    {
        return view('invoices');
    }

    public function getInvoices()
    {
        $invoices = Invoice::all();

        return response()->json($invoices);
    }

    public function getInvoicesByLoad($loadNumber)
    {
        $invoices = Invoice::where('load_number', $loadNumber)->get();

        return response()->json($invoices);
    }

    public function indexInvoiceDetails()
    {
        return view('invoice_details');
    }

    public function getInvoiceDetails($invoiceNumber)
    {
        $invoices = Invoice::where('invoice_number', $invoiceNumber)->first();

        if ($invoices) {
            error_log('Invoice Number: ' . $invoiceNumber);
        } else {
            error_log('No invoice found with number: ' . $invoiceNumber);
        }

        return response()->json($invoices);
    }

    public function saveInvoiceDetails(Request $request, $invoiceNumber)
    {
        $invoice = Invoice::where('invoice_number', $invoiceNumber)->first();

        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found.'], 404);
        }

        $invoice->fill($request->all());
        $invoice->save();

        return response()->json(['message' => 'Invoice details updated successfully']);
    }

    public function deleteInvoice($invoiceNumber)
    {
        try {
            Invoice::where('invoice_number', $invoiceNumber)->delete();
            return response()->json(['message' => 'Invoice deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting invoice', 'error' => $e->getMessage()], 500);
        }
    }

    public function createInvoice(Request $request)
    {
        $data = $request->all();

        try {
            $invoice = Invoice::create($data);
            return response()->json(['message' => 'Invoice created successfully', 'invoice' => $invoice], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating invoice', 'error' => $e->getMessage()], 500);
        }
    }

    //Hierarchical
    public function deleteInvoiceH(Request $request, $loadNumber, $invoiceNumber)
    {
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $tokenParts = explode(".", $bearerToken);

        $payload = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', $tokenParts[1]))), true);

        if (!$payload || !isset($payload['jti'])) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $jti = $payload['jti'];

        $tokenRecord = DB::table('oauth_access_tokens')
            ->where('id', $jti)
            ->first();

        if ($tokenRecord && !$tokenRecord->revoked && Carbon::parse($tokenRecord->expires_at)->isFuture()) {
            $invoice = Invoice::with('loadRelation')->where('invoice_number', $invoiceNumber)->first();

            if (!$invoice || !$invoice->loadRelation || $invoice->loadRelation->load_number != $loadNumber) {
                return response()->json(['message' => 'Invoice not found'], 400);
            }

            Comment::where('invoice_number', $invoice->invoice_number)->delete();

            $invoice->delete();

            return response()->json(['message' => 'Invoice deleted sucesfully'], 200);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }

    public function insertInvoiceH(Request $request, $loadNumber)
    {
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $tokenParts = explode(".", $bearerToken);

        $payload = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', $tokenParts[1]))), true);

        if (!$payload || !isset($payload['jti'])) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $jti = $payload['jti'];

        $tokenRecord = DB::table('oauth_access_tokens')
            ->where('id', $jti)
            ->first();

        if ($tokenRecord && !$tokenRecord->revoked && Carbon::parse($tokenRecord->expires_at)->isFuture()) {
            $validated = $request->validate([
                'invoice_status' => 'required|string|max:50',
                'invoice_date' => 'required|date',
                'invoice_due_date' => 'required|date',
                'billing_contact' => 'required|string|max:50',
                'freight_charges' => 'required|numeric',
                'fuel_surcharge' => 'required|numeric',
            ]);

            $load = Load::where('load_number', $loadNumber)->first();
            if (!$load) {
                return response()->json(['message' => 'Load not found'], 400);
            }

            $invoice = new Invoice;
            $invoice->load_number = $load->load_number;
            $invoice->invoice_status = $validated['invoice_status'];
            $invoice->invoice_date = $validated['invoice_date'];
            $invoice->invoice_due_date = $validated['invoice_due_date'];
            $invoice->billing_contact = $validated['billing_contact'];
            $invoice->freight_charges = $validated['freight_charges'];
            $invoice->fuel_surcharge = $validated['fuel_surcharge'];
            $invoice->save();

            return response()->json(['message' => 'Invoice created successfully', 'invoice' => $invoice]);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }

    public function editInvoiceH(Request $request, $loadNumber, $invoiceNumber)
    {
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $tokenParts = explode(".", $bearerToken);

        $payload = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', $tokenParts[1]))), true);

        if (!$payload || !isset($payload['jti'])) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $jti = $payload['jti'];

        $tokenRecord = DB::table('oauth_access_tokens')
            ->where('id', $jti)
            ->first();

        if ($tokenRecord && !$tokenRecord->revoked && Carbon::parse($tokenRecord->expires_at)->isFuture()) {
            $validated = $request->validate([
                'invoice_status' => 'required|string|max:50',
                'invoice_date' => 'required|date',
                'invoice_due_date' => 'required|date',
                'billing_contact' => 'required|string|max:50',
                'freight_charges' => 'required|numeric',
                'fuel_surcharge' => 'required|numeric',
            ]);

            $invoice = Invoice::where('invoice_number', $invoiceNumber)->where('load_number', $loadNumber)->first();

            if (!$invoice) {
                return response()->json(['message' => 'Invoice not found'], 400);
            }

            $invoice->invoice_status = $validated['invoice_status'];
            $invoice->invoice_date = $validated['invoice_date'];
            $invoice->invoice_due_date = $validated['invoice_due_date'];
            $invoice->billing_contact = $validated['billing_contact'];
            $invoice->freight_charges = $validated['freight_charges'];
            $invoice->fuel_surcharge = $validated['fuel_surcharge'];
            $invoice->save();

            return response()->json(['message' => 'Invoice updated successfully', 'invoice' => $invoice]);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }

    public function getInvoiceH(Request $request, $loadNumber, $invoiceNumber)
    {
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $tokenParts = explode(".", $bearerToken);

        $payload = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', $tokenParts[1]))), true);

        if (!$payload || !isset($payload['jti'])) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $jti = $payload['jti'];

        $tokenRecord = DB::table('oauth_access_tokens')
            ->where('id', $jti)
            ->first();

        if ($tokenRecord && !$tokenRecord->revoked && Carbon::parse($tokenRecord->expires_at)->isFuture()) {
            $invoice = Invoice::with('loadRelation')
                ->where('invoice_number', $invoiceNumber)
                ->whereHas('loadRelation', function ($query) use ($loadNumber) {
                    $query->where('load_number', $loadNumber);
                })
                ->first();

            if (!$invoice) {
                return response()->json(['message' => 'Invoice not found'], 404);
            }

            return response()->json(['message' => 'Invoice retrieved successfully', 'invoice' => $invoice]);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }

    public function getAllInvoicesH(Request $request, $loadNumber)
    {
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $tokenParts = explode(".", $bearerToken);

        $payload = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', $tokenParts[1]))), true);

        if (!$payload || !isset($payload['jti'])) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        $jti = $payload['jti'];

        $tokenRecord = DB::table('oauth_access_tokens')
            ->where('id', $jti)
            ->first();

        if ($tokenRecord && !$tokenRecord->revoked && Carbon::parse($tokenRecord->expires_at)->isFuture()) {
            $invoices = Invoice::with('loadRelation')
                ->whereHas('loadRelation', function ($query) use ($loadNumber) {
                    $query->where('load_number', $loadNumber);
                })
                ->get();

            return response()->json(['message' => 'Invoices retrieved successfully', 'invoices' => $invoices]);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }
}
