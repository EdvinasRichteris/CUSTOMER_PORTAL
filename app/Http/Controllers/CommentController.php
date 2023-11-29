<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Comment;
use App\Models\Load;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function indexCommentDetails()
    {
        return view('comment_details');
    }

    public function getCommentsAll()
    {
        $comments = Comment::all();

        return response()->json($comments);
    }

    public function getCommentsByInvoice($invoiceNumber)
    {
        $comments = Comment::with('user')->where('invoice_number', $invoiceNumber)->get();
        return response()->json($comments);
    }

    public function addComment(Request $request)
    {
        $comment = new Comment();
        $comment->setAttribute('invoice_number', $request->invoiceNumber);
        $comment->setAttribute('user', $request->user);
        $comment->setAttribute('comment', $request->comment_text);
        $comment->save();

        return response()->json(['message' => 'Comment added successfully.']);
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);

        if ($comment) {
            $comment->delete();
            return response()->json(['message' => 'Comment deleted successfully.']);
        } else {
            return response()->json(['message' => 'Comment not found.'], 404);
        }
    }

    public function editComment(Request $request, $commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found.'], 404);
        }

        $comment->setAttribute('comment', $request->comment_text);
        $comment->save();

        return response()->json(['message' => 'Comment updated successfully.']);
    }

    public function deleteAllInvoiceComments($invoiceNumber)
    {
        try {
            Comment::where('invoice_number', $invoiceNumber)->delete();
            return response()->json(['message' => 'Comments deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting comments', 'error' => $e->getMessage()], 500);
        }
    }

    //Hierarchical
    public function deleteCommentH($loadNumber, $invoiceNumber, $commentId)
    {

        $comment = Comment::with('invoice.loadRelation')->find($commentId);

        if (
            $comment &&
            $comment->invoice &&
            $comment->invoice->invoice_number == $invoiceNumber &&
            $comment->invoice->loadRelation &&
            $comment->invoice->loadRelation->load_number == $loadNumber
        ) {
            $comment->delete();
            return response()->json(['message' => 'Comment deleted successfully']);
        }

        return response()->json(['message' => 'Comment not found'], 400);
    }


    public function insertCommentH(Request $request, $loadNumber, $invoiceNumber)
    {
        $validated = $request->validate([
            'comment_text' => 'required|string|max:255',
        ]);

        $load = Load::where('load_number', $loadNumber)->first();
        if (!$load) {
            return response()->json(['message' => 'Load not found'], 400);
        }

        $invoice = Invoice::where('invoice_number', $invoiceNumber)->where('load_number', $load->load_number)->first();
        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found'], 400);
        }

        $comment = new Comment;
        $comment->comment = $validated['comment_text'];
        $comment->invoice_number = $invoice->invoice_number;
        $comment->user = '1';
        $comment->save();

        return response()->json(['message' => 'Comment added successfully', 'comment' => $comment]);
    }

    public function editCommentH(Request $request, $loadNumber, $invoiceNumber, $commentId)
    {
        $validated = $request->validate([
            'comment_text' => 'required|string|max:255',
        ]);

        $comment = Comment::with('invoice.loadRelation')
            ->where('id', $commentId)
            ->first();

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 400);
        }

        $comment->comment = $validated['comment_text'];
        $comment->save();
        return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment]);
    }

    public function getCommentH($loadNumber, $invoiceNumber, $commentId)
    {
        $comment = Comment::with('invoice.loadRelation')
            ->where('id', $commentId)
            ->first();

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        return response()->json(['message' => 'Comment retrieved successfully', 'comment' => $comment]);
    }

    public function getAllCommentsH($loadNumber, $invoiceNumber)
    {
        $comments = Comment::with('invoice.loadRelation')
            ->whereHas('invoice', function ($query) use ($loadNumber, $invoiceNumber) {
                $query->where('load_number', $loadNumber)->where('invoice_number', $invoiceNumber);
            })
            ->get();

        return response()->json(['message' => 'Comments retrieved successfully', 'comments' => $comments]);
    }
}
