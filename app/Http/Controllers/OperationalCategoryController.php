<?php

namespace App\Http\Controllers;

use App\Models\OperationalCategory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OperationalCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = OperationalCategory::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->withCount('expenses')->orderBy('name')->paginate(20);

        // Stats
        $totalCategories = OperationalCategory::count();
        $categoriesWithExpenses = OperationalCategory::has('expenses')->count();
        $totalExpensesCount = OperationalCategory::withCount('expenses')->get()->sum('expenses_count');

        return view('operasional.kategori.index', compact(
            'categories', 'totalCategories', 'categoriesWithExpenses', 'totalExpensesCount'
        ));
    }

    /**
     * Export Kategori to CSV
     */
    public function export()
    {
        $categories = OperationalCategory::withCount('expenses')->orderBy('name')->get();

        $filename = 'kategori-operasional-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($categories) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama Kategori', 'Deskripsi', 'Jumlah Penggunaan']);
            
            foreach ($categories as $cat) {
                fputcsv($file, [
                    $cat->name,
                    $cat->description ?? '-',
                    $cat->expenses_count,
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function create()
    {
        return view('operasional.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'description' => 'nullable|string']);
        OperationalCategory::create($request->all());
        return redirect()->route('operasional.kategori.index')->with('success', 'Kategori operasional berhasil ditambahkan.');
    }

    public function edit(OperationalCategory $kategori)
    {
        return view('operasional.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, OperationalCategory $kategori)
    {
        $request->validate(['name' => 'required|string|max:255', 'description' => 'nullable|string']);
        $kategori->update($request->all());
        return redirect()->route('operasional.kategori.index')->with('success', 'Kategori operasional berhasil diperbarui.');
    }

    public function destroy(OperationalCategory $kategori)
    {
        $kategori->delete();
        return redirect()->route('operasional.kategori.index')->with('success', 'Kategori operasional berhasil dihapus.');
    }
}
