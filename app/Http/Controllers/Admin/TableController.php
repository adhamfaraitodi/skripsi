<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TableController extends Controller
{
    public function index(){
        $datas=Table::all();
        return view('admin/table',compact('datas'));
    }
    public function create(Request $request)
    {
        $request->validate([
            'total' => 'required|integer|min:1',
        ]);

        $total = $request->total;
        $currentCount = Table::count();
        $deletedCount = Table::onlyTrashed()->count();

        if ($total < $currentCount) {
            Table::orderBy('id', 'desc')->limit($currentCount - $total)->delete();
        } elseif ($total > $currentCount + $deletedCount) {
            Table::onlyTrashed()->limit($total - $currentCount)->restore();
            $remainingToCreate = $total - (Table::count() + Table::onlyTrashed()->count());
            for ($i = 1; $i <= $remainingToCreate; $i++) {
                Table::create([
                    'number' => str_pad(Table::count() + 1, 2, '0', STR_PAD_LEFT),
                    'table_code' => $this->generateTableCode(),
                ]);
            }
        } else {
            Table::onlyTrashed()->limit($total - $currentCount)->restore();
        }
        return redirect()->back()->with('success', 'Table count has changed');
    }
    private function generateTableCode(): string {
        return Str::upper(Str::random(8));
    }
}
