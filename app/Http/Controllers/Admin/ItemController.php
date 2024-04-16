<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreItemRequest;
use App\Http\Requests\Admin\UpdateItemRequest;
use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:item list', ['only' => ['index']]);
        $this->middleware('can:item create', ['only' => ['create', 'store']]);
        $this->middleware('can:item edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:item delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $items = (new Item())->newQuery();

        if (request()->has('search')) {
            $items->where('name', 'Like', '%'.request()->input('search').'%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $items->orderBy($attribute, $sort_order);
        } else {
            $items->latest();
        }

        $items = $items->paginate(config('admin.paginate.per_page'))
        ->onEachSide(config('admin.paginate.each_side'))
        ->appends(request()->query());

        return Inertia::render('Admin/Item/Index', [
            'items' => $items,
            'filters' => request()->all('search'),
            'can' => [
                'create' => Auth::user()->can('item create'),
                'edit'   => Auth::user()->can('item edit'),
                'delete' => Auth::user()->can('item delete'),
                'manage' => Auth::user()->can('item index'),
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
        return Inertia::render('Admin/Item/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreItemRequest $request)
    {
        Item::create([
            'name'  => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->route('admin.items.index')
            ->with('message', 'item created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Inertia\Response
     */
    public function edit(Item $item)
    {
        return Inertia::render('Admin/Item/Edit', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $item->update($request->all());

        return redirect()->route('admin.items.index')
            ->with('message', 'item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('admin.items.index')
            ->with('message', __('item deleted successfully'));
    }
}
