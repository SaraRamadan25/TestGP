<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\InstructionResource;
use App\Models\Instruction;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InstructionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return InstructionResource::collection(Instruction::all());
    }
}
