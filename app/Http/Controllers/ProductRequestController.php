<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\ProductRequest::with(['user', 'product', 'fromWarehouse', 'toWarehouse'])->latest();

        // Admin3/Admin4 hanya melihat request mereka sendiri. Supervisor melihat semua.
        $role = strtolower(auth()->user()->role);
        if ($role !== 'supervisor') {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('status', 'like', '%'.$q.'%')
                    ->orWhere('type', 'like', '%'.$q.'%')
                    ->orWhereHas('product', function ($p) use ($q) {
                        $p->where('name', 'like', '%'.$q.'%')
                            ->orWhere('sku', 'like', '%'.$q.'%');
                    })
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', '%'.$q.'%')
                            ->orWhere('role', 'like', '%'.$q.'%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $totalCount = (clone $query)->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        $approvedCount = (clone $query)->where('status', 'approved')->count();
        $rejectedCount = (clone $query)->where('status', 'rejected')->count();
        $completedCount = (clone $query)->where('status', 'completed')->count();

        $requests = $query->paginate(15)->withQueryString();

        return view('gudang.request.index', compact(
            'requests',
            'role',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'completedCount',
        ));
    }

    public function create()
    {
        $products = \App\Models\Product::orderBy('name')->get();
        $warehouses = \App\Models\Warehouse::where('active', true)->orderBy('name')->get();

        return view('gudang.request.create', compact('products', 'warehouses'));
    }

    public function store(\App\Http\Requests\StoreProductRequestRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';
        $data['quantity'] = (int) ($data['quantity'] ?? 0);

        if (($data['type'] ?? null) === 'transfer') {
            $role = strtolower(auth()->user()->role);
            $data['from_warehouse_id'] = 1;
            if (empty($data['to_warehouse_id'])) {
                if ($role === 'admin4') {
                    $data['to_warehouse_id'] = 2;
                } else {
                    return back()->withInput()->with('error', 'Gudang tujuan wajib dipilih untuk permintaan transfer.');
                }
            }
        } else {
            $data['from_warehouse_id'] = null;
            $data['to_warehouse_id'] = null;
        }

        \App\Models\ProductRequest::create($data);

        return redirect()->route('gudang.request.index')
            ->with('success', 'Permintaan barang berhasil diajukan.');
    }

    public function updateStatus(Request $request, \App\Models\ProductRequest $productRequest)
    {
        if (strtolower(auth()->user()->role) !== 'supervisor') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected,completed',
            'supervisor_notes' => 'nullable|string',
        ]);

        $payload = [
            'status' => $request->status,
            'supervisor_notes' => $request->supervisor_notes,
        ];

        if ($request->status === 'approved') {
            $payload['approved_by'] = auth()->id();
            $payload['approved_at'] = now();

            if ($productRequest->type === 'transfer') {
                $payload['from_warehouse_id'] = $productRequest->from_warehouse_id ?: 1;
                $payload['to_warehouse_id'] = $productRequest->to_warehouse_id ?: 2;
            }
        }

        $productRequest->update($payload);

        return back()->with('success', 'Status permintaan berhasil diperbarui.');
    }
}
