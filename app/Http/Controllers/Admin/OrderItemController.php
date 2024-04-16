<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreOrderItemRequest;
use App\Http\Requests\Admin\UpdateOrderItemRequest;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:order-item list', ['only' => ['index']]);
        $this->middleware('can:order-item create', ['only' => ['create', 'store']]);
        $this->middleware('can:order-item edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:order-item delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $orderItems = (new OrderItem())->newQuery();

        if (request()->has('search')) {
            $orderItems->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $orderItems->orderBy($attribute, $sort_order);
        } else {
            $orderItems->latest();
        }

        $orderItems = $orderItems->paginate(config('admin.paginate.per_page'))
        ->onEachSide(config('admin.paginate.each_side'))
        ->appends(request()->query());

        return Inertia::render('Admin/OrderItem/Index', [
            'orderItems' => $orderItems,
            'filters' => request()->all('search'),
            'can' => [
                'create' => Auth::user()->can('orderItem create'),
                'edit'   => Auth::user()->can('orderItem edit'),
                'delete' => Auth::user()->can('orderItem delete'),
                'manage' => Auth::user()->can('orderItem.item index'),
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
        return Inertia::render('Admin/OrderItem/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreOrderItemRequest $request)
    {
        OrderItem::create([
            'name'   => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.order-items.index')
            ->with('message', 'Order-Item created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Inertia\Response
     */
    public function edit(OrderItem $orderItem)
    {
        return Inertia::render('Admin/OrderItem/Edit', [
            'orderItem' => $orderItem,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateOrderItemRequest $request, OrderItem $orderItem)
    {
        $orderItem->update($request->all());

        return redirect()->route('admin.order-items.index')
            ->with('message', 'Order-Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();

        return redirect()->route('admin.order-items.index')
            ->with('message', __('Order-Item deleted successfully'));
    }
}
