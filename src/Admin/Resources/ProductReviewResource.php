<?php

namespace SmartCms\Reviews\Admin\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea as ComponentsTextarea;
use SmartCms\Core\Services\Schema;
use SmartCms\Core\Services\TableSchema;
use SmartCms\Reviews\Admin\Resources\ProductReviewResource\Pages;
use SmartCms\Reviews\Models\ProductReview;

class ProductReviewResource extends Resource
{
    protected static ?string $model = ProductReview::class;

    protected static ?int $navigationSort = 11;

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
        return _nav('catalog');
    }

    public static function getModelLabel(): string
    {
        return __('reviews::trans.nav');
    }

    public static function getPluralModelLabel(): string
    {
        return __('reviews::trans.nav_plural');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Schema::getName()->required()->disabled(),
                Schema::getStatus(),
                Schema::getSelect('rating', [
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                ])->label(_columns('rating'))->required()->default(5)->disabled(),
                ComponentsTextarea::make('comment')->label(_columns('comment'))->characterLimit(250)->disabled(),
                ComponentsTextarea::make('admin_comment')->label(__('reviews::trans.admin_comment'))->characterLimit(250),
                // Schema::getImage(path: 'reviews', isMultiple: true),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('product.name')->label(_columns('product')),
                TableSchema::getStatus(),
                TextColumn::make('name')->label(_columns('name')),
                TextColumn::make('rating')->numeric()->label(_columns('rating')),
                TextColumn::make('email')->label(_columns('email')),
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
