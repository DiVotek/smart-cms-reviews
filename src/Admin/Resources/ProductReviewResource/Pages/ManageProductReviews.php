<?php

namespace SmartCms\Reviews\Admin\Resources\ProductReviewResource\Pages;

use Filament\Actions;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRecords;
use SmartCms\Reviews\Admin\Resources\ProductReviewResource;

class ManageProductReviews extends ManageRecords
{
    protected static string $resource = ProductReviewResource::class;

    protected function getHeaderActions(): array
    {
        $user_notification_fields = [];
        foreach (get_active_languages() as $lang) {
            $user_notification_fields[] = Hidden::make('reviews.user_notification.'.$lang->slug);
        }

        return [
            Actions\Action::make('help')
                ->help('Reviews help'),
            ActionsAction::make('settings')
                ->settings()
                ->form([
                    ...$user_notification_fields,
                    TextInput::make('reviews.user_notification.default')
                        ->label(__('reviews::trans.user_notification'))
                        ->suffixAction(Action::make(_fields('translates'))
                            ->hidden(function () {
                                return ! is_multi_lang();
                            })
                            ->icon(function () {
                                $translates = setting('reviews.user_notification', []);
                                $translates = array_filter($translates);
                                if (count($translates) == count(get_active_languages()) + 1) {
                                    return 'heroicon-o-check-circle';
                                }

                                return 'heroicon-o-exclamation-circle';
                            })->form(function ($form) {
                                $fields = [];
                                $languages = get_active_languages();
                                foreach ($languages as $language) {
                                    $fields[] = TextInput::make($language->slug)->label(__('reviews::trans.user_notification').' ('.$language->name.')');
                                }

                                return $form->schema($fields);
                            })
                            ->fillForm(function () {
                                return setting('reviews.user_notification', []);
                            })
                            ->action(function ($data, $set) {
                                foreach ($data as $key => $value) {
                                    $set('reviews.user_notification.'.$key, $value);
                                }
                            })),
                ])
                ->fillForm(function () {
                    return [
                        'reviews' => [
                            'user_notification' => setting('reviews.user_notification', []),
                        ],
                    ];
                })
                ->action(function (array $data) {
                    setting([
                        'reviews.user_notification' => $data['reviews']['user_notification'] ?? [],
                    ]);
                }),
            Actions\CreateAction::make()->create(),
        ];
    }
}
