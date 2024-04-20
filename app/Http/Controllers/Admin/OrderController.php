<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\StoreOrderRequest;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $orders->where('customer_name', 'Like', '%' . request()->input('search') . '%');
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
        $itemOptions = Item::selectOptions();
        return Inertia::render('Admin/Order/Create', ['itemOptions' => $itemOptions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreOrderRequest $request)
    {
        $totalPrice = 0;
        $orderItems = [];
        collect($request->input('items'))->map(function ($item) use (&$totalPrice, &$orderItems) {
            $totalItemPrice = Order::calculateTotalItemPrice($item['price'], $item['count']);
            $orderItems[] = [
                'item_id'    => Item::findOrFail($item['id'])->id,
                'order_id'   => null,
                'item_price' => $item['price'],
                'item_count' => $item['count'],
                'item_total' => $totalItemPrice,
            ];
            return $totalPrice += $totalItemPrice;
        });

        DB::transaction(function () use ($request, $totalPrice, $orderItems) {
            $order = Order::create([
                'customer_name'     => $request->input('customer_name'),
                'customer_address'  => $request->input('customer_address'),
                'order_description' => $request->input('order_description'),
                'total_price'       => $totalPrice
            ]);

            foreach ($orderItems as $orderItem) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'item_id'    => $orderItem['item_id'],
                    'item_price' => $orderItem['item_price'],
                    'item_count' => $orderItem['item_count'],
                    'item_total' => $orderItem['item_total'],
                ]);
            }
        });

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
        $itemOptions = Item::selectOptions();
        $orderItems = $order->orderItems;
        $orderItems = $orderItems->map(function ($item) {
            return [
                'items'      => $item->item_id,
                'name'       => Item::find($item->item_id)->name,
                'item_price' => $item->item_price,
                'item_count' => $item->item_count,
                'item_total' => $item->item_total,
            ];
        });

        return Inertia::render('Admin/Order/Edit', [
            'order'       => $order,
            'itemOptions' => $itemOptions,
            'orderItems'  => $orderItems,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreOrderRequest $request, Order $order)
    {
        $totalPrice = 0;
        $orderItems = [];
        $oldOrderItems = $order->orderItems;

        collect($request->input('items'))->map(function ($item) use (&$totalPrice, &$orderItems, $order) {
            $totalItemPrice = Order::calculateTotalItemPrice($item['price'], $item['count']);
            $orderItems[] = [
                'item_id'    => Item::findOrFail($item['id'])->id,
                'order_id'   => $order->id,
                'item_price' => $item['price'],
                'item_count' => $item['count'],
                'item_total' => $totalItemPrice,
            ];
            return $totalPrice += $totalItemPrice;
        });

        DB::transaction(function () use ($request, $totalPrice, $orderItems, $order, $oldOrderItems) {
            Order::where('id', $order->id)->update([
                'customer_name'     => $request->input('customer_name'),
                'customer_address'  => $request->input('customer_address'),
                'order_description' => $request->input('order_description'),
                'total_price'       => $totalPrice
            ]);

            foreach ($oldOrderItems as $orderItem) {
                $checkItemExists = array_search($orderItem->item_id, array_column($request->input('items'), 'id'));
                if (!$checkItemExists) {
                    $orderItem->delete();
                }
            }

            foreach ($orderItems as $orderItem) {
                OrderItem::updateOrCreate(
                    [
                        'order_id'   => $order->id,
                        'item_id'    => $orderItem['item_id'],
                    ],
                    [
                        'item_price' => $orderItem['item_price'],
                        'item_count' => $orderItem['item_count'],
                        'item_total' => $orderItem['item_total'],
                    ]
                );
            }
        });

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
