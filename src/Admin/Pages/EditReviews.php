<?php

namespace SmartCms\Reviews\Admin\Pages;

use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea as ComponentsTextarea;
use SmartCms\Core\Services\Schema;
use SmartCms\Core\Services\TableSchema;
use SmartCms\Store\Admin\Resources\ProductResource;
use SmartCms\Store\Admin\Resources\ProductResource\Pages\ListProducts;
use SmartCms\Store\Models\Product;

class EditReviews extends ManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'reviews';

    public static function getNavigationBadge(): ?string
    {
        $pageId = request()->route('record', 0);

        return Product::query()->find($pageId)?->reviews()?->count() ?? 0;
    }

    public static function getNavigationLabel(): string
    {
        return __('reviews::trans.nav');
    }


    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-star';
    }

    public function getBreadcrumb(): string
    {
        /** @var \SmartCms\Store\Models\Product $record */
        $record = $this->record;

        return $record->name;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Schema::getName()->required(),
                Schema::getStatus(),
                Schema::getSelect('rating', [
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                ])->label(_columns('rating'))->required()->default(5),
                ComponentsTextarea::make('comment')->label(_columns('comment'))->characterLimit(250),
                ComponentsTextarea::make('admin_comment')->label(__('reviews::trans.admin_comment'))->characterLimit(250),
                Schema::getImage(path: 'reviews', isMultiple: true),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->label(_columns('name')),
                TableSchema::getStatus(),
                TextColumn::make('rating')->numeric()->label(_columns('rating')),
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
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
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

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make()->icon('heroicon-o-x-circle'),
            \Filament\Actions\ViewAction::make()
                ->url(fn($record) => $record->route())
                ->icon('heroicon-o-arrow-right-end-on-rectangle')
                ->openUrlInNewTab(true),
            \Filament\Actions\Action::make(_actions('save_close'))
                ->label('Save & Close')
                ->icon('heroicon-o-check-badge')
                ->formId('form')
                ->action(function () {
                    $this->getOwnerRecord()->touch();

                    return redirect()->to(ListProducts::getUrl());
                }),
            \Filament\Actions\Action::make(_actions('save'))
                ->label(_actions('save'))
                ->icon('heroicon-o-check-circle')
                ->formId('form')
                ->action(function () {
                    $this->getOwnerRecord()->touch();
                }),
        ];
    }
}
