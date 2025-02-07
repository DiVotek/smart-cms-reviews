<?php

namespace SmartCms\Reviews\Admin\Pages;

use Closure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard;
use Illuminate\Contracts\Support\Htmlable;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;
use SmartCms\Core\Models\Field;

class ReviewSettings extends BaseSettings
{
    public static function getNavigationGroup(): ?string
    {
        return _nav('communication');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return null;
    }

    public static function getNavigationLabel(): string
    {
        return __('reviews::trans.navigation_label');
    }

    public function getBreadcrumbs(): array
    {
        return [
            Dashboard::getUrl() => _nav('dashboard'),
            _nav('settings'),
        ];
    }

    public function schema(): array|Closure
    {
        return [
            Repeater::make('reviews.fields')
                ->label('Fields')
                ->schema([
                    Select::make('field_id')
                        ->label('Field')
                        ->options(Field::query()->pluck('name', 'id')->toArray())
                        ->required(),
                ])
                ->required(),
        ];
    }
}
