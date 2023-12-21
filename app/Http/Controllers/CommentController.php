<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
    public function deleteCommentH(Request $request, $loadNumber, $invoiceNumber, $commentId)
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
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }


    public function insertCommentH(Request $request, $loadNumber, $invoiceNumber)
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
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }

    public function editCommentH(Request $request, $loadNumber, $invoiceNumber, $commentId)
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
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }

    public function getCommentH(Request $request, $loadNumber, $invoiceNumber, $commentId)
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
            $comment = Comment::with('invoice.loadRelation')
                ->where('id', $commentId)
                ->first();

            if (!$comment) {
                return response()->json(['message' => 'Comment not found'], 404);
            }

            return response()->json(['message' => 'Comment retrieved successfully', 'comment' => $comment]);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }

    public function getAllCommentsH(Request $request, $loadNumber, $invoiceNumber)
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
            $comments = Comment::with('invoice.loadRelation')
                ->whereHas('invoice', function ($query) use ($loadNumber, $invoiceNumber) {
                    $query->where('load_number', $loadNumber)->where('invoice_number', $invoiceNumber);
                })
                ->get();

            return response()->json(['message' => 'Comments retrieved successfully', 'comments' => $comments]);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }
}
