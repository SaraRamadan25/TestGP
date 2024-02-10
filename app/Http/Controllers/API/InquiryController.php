<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInquiryRequest;
use App\Http\Resources\InquiryResource;
use App\Models\Inquiry;
use Illuminate\Http\Response;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request): InquiryResource
    {
        $inquiry = Inquiry::create($request->validated());
        return new InquiryResource($inquiry);
    }

    public function show(Inquiry $inquiry): InquiryResource
    {
        return new InquiryResource($inquiry);
    }

    public function destroy(Inquiry $inquiry): Response
    {
        $inquiry->delete();
        return response()->noContent();
    }
}
