<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Crypt;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->cursorPaginate(20); // aman untuk 10rb+ data

        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'note' => 'nullable|string',
            'category' => 'nullable|string'
        ]);

        $tx = Transaction::create([
            'user_id' => $request->user()->id,
            'amount_encrypted' => Crypt::encryptString($validated['amount']),
            'note_encrypted' => Crypt::encryptString($validated['note'] ?? ''),
            'category' => $validated['category'] ?? 'general',
        ]);

        return response()->json(['message' => 'Transaction saved', 'data' => $tx]);
    }
}
