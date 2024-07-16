<?php

namespace App\Http\Controllers;

use App\Enums\ItemType;
use App\Http\Resources\Item\ItemCollection;
use App\Http\Resources\Item\ItemResource;
use App\Models\Favorite;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function addFavorite(Item $item): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $existingFavorite = Favorite::where('user_id', $user->id)->where('item_id', $item->id)->first();
        if ($existingFavorite) {
            return response()->json(['message' => 'Item is already in favorites'], 200);
        }

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        return response()->json(['message' => 'Your item has been added to favorites successfully'], 201);
    }

    public function getFavorites(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $favorites = Favorite::where('user_id', $user->id)->get();

        // Prepare an array to store detailed item information
        $detailedFavorites = [];

        foreach ($favorites as $favorite) {
            $item = Item::find($favorite->item_id);

            if ($item) {
                $detailedFavorites[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'image' => $item->image,
                ];
            }
        }

        return response()->json($detailedFavorites);
    }}

