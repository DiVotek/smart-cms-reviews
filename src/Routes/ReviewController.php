<?php

namespace SmartCms\Reviews\Routes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SmartCms\Core\Services\ScmsResponse;
use SmartCms\Reviews\Models\ProductReview;

class ReviewController
{
    public function add(Request $request)
    {
        $validation = [
            'product_id' => 'required|exists:SmartCms\Store\Models\Product,id',
            'rating' => 'required|integer|min:1|max:5',
            'name' => 'required|string|max:255',
        ];
        $validator = Validator::make($request->all(), $validation);
        if ($validator->fails()) {
            return new ScmsResponse(false, [], $validator->errors()->toArray());
        }
        ProductReview::create([
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'status' => 0,
            'name' => $request->input('name', 'Anonymus user'),
            'email' => $request->input('email', null),
            'comment' => $request->input('comment', null),
            'images' => $request->input('images', []),
        ]);

        return new ScmsResponse;
    }
}
