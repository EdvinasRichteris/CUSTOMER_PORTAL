<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Load;
use App\Models\Invoice;
use App\Models\Comment;

class LoadController extends Controller
{
    public function index()
    {
        return view('loads');
    }

    public function getLoads()
    {
        $loads = Load::all();

        return response()->json($loads);
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
    public function deleteLoadH($loadNumber)
    {
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
    }

    public function insertLoadH(Request $request)
    {
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
    }

    public function editLoadH(Request $request, $loadNumber)
    {
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
    }

    public function getLoadH($loadNumber)
    {
        $load = Load::where('load_number', $loadNumber)->first();

        if (!$load) {
            return response()->json(['message' => 'Load not found'], 404);
        }

        return response()->json(['message' => 'Load retrieved successfully', 'load' => $load]);
    }

    public function getAllLoadsH()
    {
        $loads = Load::all();

        return response()->json(['message' => 'Loads retrieved successfully', 'loads' => $loads]);
    }
}
