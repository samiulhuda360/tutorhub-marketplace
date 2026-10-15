<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Offers\OfferDrafter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** "Describe what you need": the parent's own words in, a draft of the offer form out. */
class OfferDraftController extends Controller
{
    public function __invoke(Request $request, OfferDrafter $drafter): JsonResponse
    {
        $data = $request->validate(['request' => ['required', 'string', 'min:10', 'max:1500']]);

        return response()->json($drafter->draft($data['request'])->toArray());
    }
}
