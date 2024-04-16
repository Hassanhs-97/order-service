<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:order list', ['only' => ['index']]);
        $this->middleware('can:order create', ['only' => ['create', 'store']]);
        $this->middleware('can:order edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:order delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $orders = (new Order())->newQuery();

        if (request()->has('search')) {
            $orders->where('name', 'Like', '%' . request()->input('search') . '%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $orders->orderBy($attribute, $sort_order);
        } else {
            $orders->latest();
        }

        $orders = $orders->paginate(config('admin.paginate.per_page'))
            ->onEachSide(config('admin.paginate.each_side'))
            ->appends(request()->query());

        return Inertia::render('Admin/Order/Index', [
            'orders' => $orders,
            'filters' => request()->all('search'),
            'can' => [
                'create' => Auth::user()->can('order create'),
                'edit'   => Auth::user()->can('order edit'),
                'delete' => Auth::user()->can('order delete'),
                'manage' => Auth::user()->can('order index'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Order/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreOrderRequest $request)
    {
        Order::create([
            'name'  => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('message', 'order created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Inertia\Response
     */
    public function edit(Order $order)
    {
        return Inertia::render('Admin/Order/Edit', [
            'order' => $order,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->all());

        return redirect()->route('admin.orders.index')
            ->with('message', 'order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('message', __('order deleted successfully'));
    }
}
