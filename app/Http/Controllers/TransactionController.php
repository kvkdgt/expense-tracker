<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Transaction::forUser($user->id)->with('category');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('transaction_date', '<=', $request->end_date);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $transactions = $query->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->paginate(20);

        $categories = Category::where('user_id', $user->id)
            ->orderByDesc('usage_count')
            ->get();

        return view('transactions.index', compact('transactions', 'categories'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:income,expense'],
            'category_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();

        // Find or create category
        $category = Category::firstOrCreate(
            [
                'user_id' => $user->id,
                'name' => trim($request->category_name),
                'type' => $request->type,
            ]
        );
        $category->incrementUsage();

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'type' => $request->type,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'note' => $request->note,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Transaction added successfully!']);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction added successfully!');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $request->validate([
            'type' => ['required', 'in:income,expense'],
            'category_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();

        // Find or create category
        $category = Category::firstOrCreate(
            [
                'user_id' => $user->id,
                'name' => trim($request->category_name),
                'type' => $request->type,
            ]
        );
        $category->incrementUsage();

        $transaction->update([
            'category_id' => $category->id,
            'type' => $request->type,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'note' => $request->note,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated!');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted!');
    }

    public function suggestions(Request $request)
    {
        $user = Auth::user();
        $type = $request->input('type', 'expense');
        $search = $request->input('q', '');

        $categories = Category::where('user_id', $user->id)
            ->where('type', $type)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderByDesc('usage_count')
            ->limit(10)
            ->pluck('name');

        return response()->json($categories);
    }
}






