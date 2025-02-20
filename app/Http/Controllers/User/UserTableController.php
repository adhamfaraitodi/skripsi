<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserTableController extends Controller
{
    public function index(){
        $datas= Table::withoutTrashed()->get();
        return view('user/table_user',compact('datas'));
    }
    public function scan($id){
        $data = Table::where('table_code', $id)->firstOrFail();
        $orderId = $this->generateOrderId();
        Session::put('table_id', $data->id);
        Session::put('order_id', $orderId);
        return redirect()->route('user.dine-in');
    }
    private function generateOrderId()
    {
        return 'RY-' . strtoupper(Str::random(5));
    }
}
