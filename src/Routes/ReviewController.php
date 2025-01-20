<?php

namespace SmartCms\Reviews\Routes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SmartCms\Reviews\Models\ProductReview;

class ReviewController
{
   public function add(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'product_id' => 'required|exists:SmartCms\Store\Models\Product,id',
         'rating' => 'required|integer|min:1|max:5',
      ]);

      if ($validator->fails()) {
         return response()->json([
            'errors' => $validator->errors(),
         ], 422);
      }

      $productReview = ProductReview::create([
         'product_id' => $request->product_id,
         'rating' => $request->rating,
         'data' => $request->data,
         'is_approved' => false,
      ]);

      return response()->json([
         'message' => 'Review created successfully.',
         'data' => $productReview,
     ], 201);
   }
}
