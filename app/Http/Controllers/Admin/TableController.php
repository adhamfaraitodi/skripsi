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
    public function create(Request $request){
        $request->validate([
            'total' => 'required|integer|min:1',
        ]);
        $total = $request->total;
        $currentCount = Table::count();
        if ($total < $currentCount) {
            Table::orderBy('id', 'desc')->limit($currentCount - $total)->delete();
        } elseif ($total > $currentCount) {
            for ($i = $currentCount + 1; $i <= $total; $i++) {
                Table::create([
                    'number' => str_pad($i, 2, '0', STR_PAD_LEFT),
                    'table_code' => $this->generateTableCode(),
                ]);
            }
        }
        return redirect()->back()->with('success','Table count has changed');
    }
    private function generateTableCode(): string {
        return Str::upper(Str::random(8));
    }
}
