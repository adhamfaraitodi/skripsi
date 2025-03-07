<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $data = [
            'totalUsers' => $this->getTotalUsers(),
            'activity' => $this->getActivity(),
            'totalStaff' => $this->getTotalStaff(),
            'dataOrders' => $this->getOrders()
        ];
        return view('admin/dashboard', $data);
    }

    private function getTotalUsers(){
        return cache()->remember('totalUsers', 10, function() {
            return User::where('role_id', 3)->count();
        });
    }

    private function getActivity(){
        return cache()->remember('activity', 10, function() {
            $activity = [];
            for ($i = 0; $i < 7; $i++) {
                $date = now()->subDays($i)->toDateString();
                $activity[$date] = Order::whereDate('created_at', $date)->count();
            }
            return array_reverse($activity);
        });
    }

    private function getTotalStaff(){
        return cache()->remember('totalStaff', 10, function() {
            return User::where('role_id', 2)->count();
        });
    }

    private function getOrders(){
        return cache()->remember('dataOrders', 10, function() {
            return [
                'success' => Order::whereIn('order_status', ['success', 'paid'])->count(),
                'cancelled' => Order::where('order_status', 'cancelled')->count(),
                'pending' => Order::where('order_status', 'pending')->count(),
            ];
        });
    }
}
