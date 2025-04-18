<?php

namespace SmartCms\Reviews\Events;

use Filament\Forms\Components\Toggle;

class ProfileNotifications
{
    public function __invoke(array &$notifications, string $type)
    {
        $notifications[] = Toggle::make('notifications.'.$type.'.new_review')
            ->label(__('reviews::trans.new_review'));
    }
}
