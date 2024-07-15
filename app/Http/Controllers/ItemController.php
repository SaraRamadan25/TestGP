<?php

namespace App\Http\Controllers;

use App\Enums\ItemType;
use App\Http\Resources\Item\ItemCollection;
use App\Http\Resources\Item\ItemResource;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function filterItems(Request $request): JsonResponse
    {
        $type = $request->query('type');
        $query = Item::query();

        if ($type && ItemType::tryFrom($type)) {
            $query->where('type', $type);
        }

        $filteredItems = $query->get();
        return response()->json(new ItemCollection($filteredItems));

    }
    public function popular(): JsonResponse
    {
        $popularItems = Item::where('popular', true)->get();

        if ($popularItems->isEmpty()) {
            $popularItems = Item::orderBy('created_at', 'desc')->cursor();
        }
        return response()->json(new ItemCollection($popularItems));
    }
    public function show(Item $item): ItemResource
    {
        $item->load('reviews');
        return new ItemResource($item);
    }
}
