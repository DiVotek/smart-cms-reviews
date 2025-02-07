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
            Actions\Action::make(_actions('help'))
                ->iconButton()
                ->icon('heroicon-o-question-mark-circle')
                ->modalDescription(_hints('help.product_review'))
                ->modalFooterActions([]),
            Actions\Action::make('settings')->label(_actions('settings'))
                ->iconButton()
                ->icon('heroicon-o-cog-6-tooth')
                ->form([
                    Repeater::make('fields')
                        ->label('Fields')
                        ->schema([
                            Select::make('field_id')
                                ->label('Field')
                                ->options(Field::query()->pluck('name', 'id')->toArray())
                                ->required(),
                        ])
                        ->required(),
                ])->fillForm(function () {
                    return [
                        'fields' => setting('reviews.fields', []),
                    ];
                })->action(function ($data) {
                    setting([
                        'reviews.fields' => $data['fields'],
                    ]);
                }),
            Actions\CreateAction::make(),
        ];
    }
}
