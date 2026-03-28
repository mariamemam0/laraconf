<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Attendees\AttendeeResource;
use Filament\Actions\Action;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class TestChart extends Widget implements HasActions , HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;
    
    protected int | string | array $columnSpan = 'full';

    protected $listeners = ['undoNotification' => 'handleUndo'];
    public function handleUndo(): void
{
    Notification::make()
        ->success()
        ->title('Action undone!')
         ->actions(function(){
            ray('hi');
         })
        ->send();
}
    protected string $view = 'filament.widgets.test-chart';

    public function callNotification(): Action
    {
       return Action::make('callNotification')
       ->button()
       ->color('warning')
       ->label('send a notification')
       ->action(function(){
        Notification::make()->warning()->title('you sent a notification')
        ->body('This is a test')
        ->persistent()
        ->actions([
            Action::make('goToAttendees')
            ->button()
            ->color('primary')
            ->url(AttendeeResource::getUrl('edit',['record'=>1])),
        Action::make('undo')
                            ->link()
                            ->color('gray')
                           ->dispatch('undoNotification')

        ])
        ->send();
       });
    }
}
