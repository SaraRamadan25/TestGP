<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\VitalSignResource;
use App\Models\VitalSign;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VitalSignController extends Controller
{
    public function show($jacket_id) :VitalSignResource
    {
        $vitalSign = VitalSign::where('jacket_id', $jacket_id)->firstOrFail();
        return new VitalSignResource($vitalSign);
    }


    public function update(Request $request, $jacket_id): VitalSignResource
    {
        $vitalSign = VitalSign::where('jacket_id', $jacket_id)->firstOrFail();
        $vitalSign->update($request->all());
        return new VitalSignResource($vitalSign);
    }
}
