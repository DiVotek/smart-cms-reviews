<?php

namespace SmartCms\Reviews\Admin\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use SmartCms\Core\Models\Field;
use SmartCms\Core\Services\Schema;
use SmartCms\Core\Services\TableSchema;
use SmartCms\Reviews\Admin\Resources\ProductReviewResource\Pages as Pages;
use SmartCms\Reviews\Models\ProductReview;
use SmartCms\Store\Models\Product;

class ProductReviewResource extends Resource
{
    protected static ?string $model = ProductReview::class;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationGroup(): ?string
    {
        return _nav('communication');
    }

    public static function getModelLabel(): string
    {
        return _nav('review');
    }

    public static function getPluralModelLabel(): string
    {
        return _nav('reviews');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        $fields = Field::query()->whereIn('id', _settings('order.fields', []))->get();

        $formFields = [];

        foreach ($fields as $field) {
            $name = $field->name;
            $mask = null;
            if ($field->mask && isset($field->mask[main_lang()])) {
                $mask = $field->mask[main_lang()];
            }

            $textInput = TextInput::make('data.'.$name)
                ->label($field->name)
                ->required($field->required);

            if ($mask) {
                $textInput->mask($mask);
            }

            $formFields[] = $textInput;
        }

        return $form
            ->schema([
                Schema::getSelect('product_id', Product::query()->pluck('name', 'id')->toArray())->label(_columns('product'))->required()->hiddenOn('edit')->disabledOn('edit'),
                Schema::getSelect('rating', [
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                ])->label(_columns('rating'))->required()->default(5),
                ...$formFields,
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('product.name')->label(_columns('product')),
                TextColumn::make('rating')->numeric()->label(_columns('rating')),
                ToggleColumn::make('is_approved')->label(_columns('is_approved'))->afterStateUpdated(function ($record, $state) {
                    if ($record->is_approved && ! $record->status) {
                        $record->update(['status' => 1]);
                    }
                }),
                TableSchema::getUpdatedAt(),
            ])
            ->filters([
                SelectFilter::make('rating')
                    /**@phpstan-ignore-next-line */
                    ->options([
                        1 => 1,
                        2 => 2,
                        3 => 3,
                        4 => 4,
                        5 => 5,
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProductReviews::route('/'),
        ];
    }
}
