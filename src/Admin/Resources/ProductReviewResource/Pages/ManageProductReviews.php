<?php

namespace SmartCms\Reviews\Admin\Resources\ProductReviewResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ManageRecords;
use SmartCms\Core\Models\Field;
use SmartCms\Reviews\Admin\Resources\ProductReviewResource;

class ManageProductReviews extends ManageRecords
{
    protected static string $resource = ProductReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('help')
                ->help('Reviews help'),
            // Actions\Action::make('settings')->settings()
            //     ->form([
            //         Repeater::make('fields')
            //             ->label('Fields')
            //             ->schema([
            //                 Select::make('field_id')
            //                     ->label('Field')
            //                     ->options(Field::query()->pluck('name', 'id')->toArray())
            //                     ->required(),
            //             ])
            //             ->required(),
            //     ])->fillForm(function () {
            //         return [
            //             'fields' => setting('reviews.fields', []),
            //         ];
            //     })->action(function ($data) {
            //         setting([
            //             'reviews.fields' => $data['fields'],
            //         ]);
            //     }),
            Actions\CreateAction::make()->create(),
        ];
    }
}
