<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Load;
use App\Models\Invoice;
use App\Models\Comment;

class LoadController extends Controller
{
    public function index()
    {
        return view('loads');
    }

    public function getLoads(Request $request)
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
            $loads = Load::all();
            return response()->json($loads);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }

    public function indexLoadDetails($loadNumber)
    {
        return view('load_details');
    }
    public function getLoadDetails($loadNumber)
    {
        $load = Load::where('load_number', $loadNumber)->first();
        return response()->json($load);
    }


    //Hierarchical
    public function deleteLoadH(Request $request, $loadNumber)
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
            $load = Load::where('load_number', $loadNumber)->first();

            if (!$load) {
                return response()->json(['message' => 'Load not found'], 400);
            }

            $invoices = Invoice::where('load_number', $loadNumber)->get();

            foreach ($invoices as $invoice) {
                Comment::where('invoice_number', $invoice->invoice_number)->delete();
                $invoice->delete();
            }

            $load->delete();

            return response()->json(['message' => 'Load deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }

    public function insertLoadH(Request $request)
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
                'load_status' => 'required|string',
                'load_mode' => 'required|string',
                'customer_quote_total' => 'required|numeric',
                'customer_invoice_total' => 'required|numeric',
                'total_weight' => 'required|numeric',
            ]);

            $load = new Load;
            $load->load_status = $validated['load_status'];
            $load->load_mode = $validated['load_mode'];
            $load->customer_quote_total = $validated['customer_quote_total'];
            $load->customer_invoice_total = $validated['customer_invoice_total'];
            $load->total_weight = $validated['total_weight'];
            $load->save();

            return response()->json(['message' => 'Load created successfully', 'load' => $load]);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }

    public function editLoadH(Request $request, $loadNumber)
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
                'load_status' => 'required|string',
                'load_mode' => 'required|string',
                'customer_quote_total' => 'required|numeric',
                'customer_invoice_total' => 'required|numeric',
                'total_weight' => 'required|numeric',
            ]);

            $load = Load::where('load_number', $loadNumber)->first();

            if (!$load) {
                return response()->json(['message' => 'Load not found'], 400);
            }

            $load->load_status = $validated['load_status'];
            $load->load_mode = $validated['load_mode'];
            $load->customer_quote_total = $validated['customer_quote_total'];
            $load->customer_invoice_total = $validated['customer_invoice_total'];
            $load->total_weight = $validated['total_weight'];
            $load->save();

            return response()->json(['message' => 'Load updated successfully', 'load' => $load]);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }

    public function getLoadH(Request $request, $loadNumber)
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
            $load = Load::where('load_number', $loadNumber)->first();

            if (!$load) {
                return response()->json(['message' => 'Load not found'], 404);
            }

            return response()->json(['message' => 'Load retrieved successfully', 'load' => $load]);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }

    public function getAllLoadsH(Request $request)
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
            $loads = Load::all();

            return response()->json(['message' => 'Loads retrieved successfully', 'loads' => $loads]);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }
}
