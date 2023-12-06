<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AttendeeResource;
use App\Models\Attendee;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class CustomWidget extends Widget implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    protected static string $view = 'filament.widgets.custom-widget';
    protected int | string | array $columnSpan = 'full';

    public function callNotification() : Action
    {
        return Action::make('callNotification')
            ->button()
            ->color('warning')
            ->label('Send a notification')
            ->action(function() {
                Notification::make()
                    ->warning()
                    ->title('You sent a notification')
                    ->duration(3000)
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('goToAttendees')
                            ->button()
                            ->color('primary')
                            ->url(AttendeeResource::getUrl('edit', ['record' => 1])),

                        \Filament\Notifications\Actions\Action::make('undo')
                            ->link()
                            ->color('gray')
                            ->action(function () {

                            })
                    ])
                    ->body('This is a test')
                    ->send();
            });
    }
}
