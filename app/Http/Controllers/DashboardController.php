<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the Dashboard.
     */
    public function dashboard()
    {
        $totalItems  = Item::all()->count();
        $totalOrders = Order::all()->count();
        $totalUsers = User::all()->count();
        return Inertia::render('Admin/Dashboard', [
            'totalItems'  => $totalItems,
            'totalOrders' => $totalOrders,
            'totalUsers'  => $totalUsers,
        ]);
    }
}
