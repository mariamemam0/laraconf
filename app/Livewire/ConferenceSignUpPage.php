<?php

namespace App\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ConferenceSignUpPage extends Component implements HasForms, HasActions
{
    use InteractsWithActions , InteractsWithForms;


    public function signUpAction(): Action
    {
        return Action::make('signUp')
            ->slideOver()
            ->form([
                Repeater::make('attendee')
                ->schema([
]),
                TextInput::make('name'),
            ])
            ->action(function(){
                ray('hi');
            });
    }
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.conference-sign-up-page');
    }
}
