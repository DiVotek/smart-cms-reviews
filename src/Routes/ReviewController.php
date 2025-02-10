<?php

namespace SmartCms\Reviews\Routes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SmartCms\Core\Models\Field;
use SmartCms\Core\Services\ScmsResponse;
use SmartCms\Reviews\Models\ProductReview;

class ReviewController
{
    public function add(Request $request)
    {
        $validation = [
            'product_id' => 'required|exists:SmartCms\Store\Models\Product,id',
            'rating' => 'required|integer|min:1|max:5',
        ];
        // $fastOrderFields = setting('reviews.fields', []);
        // $customAttributes = [];
        // foreach ($fastOrderFields as $field) {
        //     $field = Field::query()->find($field['field_id']);
        //     if ($field->required) {
        //         $validation[strtolower($field->html_id)] = 'required';
        //     }
        //     if ($field->validation) {
        //         $validation[strtolower($field->html_id)] = $field->validation;
        //     }
        //     $customAttributes[strtolower($field->html_id)] = $field->label[current_lang()] ?? $field->label[main_lang()] ?? '';
        // }
        $validator = Validator::make($request->all(), $validation);
        // $validator->setAttributeNames($customAttributes);
        if ($validator->fails()) {
            return new ScmsResponse(false, [], $validator->errors()->toArray());
        }
        // $data = [];
        // foreach ($request->except(['_token', 'product_id', 'rating']) as $key => $value) {
        //     $field = Field::query()->where('html_id', $key)->first();
        //     if ($field) {
        //         $data[$field->name] = $value;
        //     }
        // }
        $data = $request->except(['_token', 'product_id', 'rating']);
        ProductReview::create([
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'data' => $data,
            'is_approved' => false,
        ]);

        return new ScmsResponse;
    }
}
