<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInquiryRequest;
use App\Http\Resources\InquiryResource;
use App\Models\Inquiry;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request): InquiryResource
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = Auth::id();

        $inquiry = Inquiry::create($validatedData);
        return new InquiryResource($inquiry);
    }
}
