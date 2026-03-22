<?php

namespace App\Livewire;

use App\Models\Attendee;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ConferenceSignUpPage extends Component implements HasForms, HasActions
{
    use InteractsWithActions , InteractsWithForms;

    public int $conferenceId;
    public int $price = 50000;
    public function mount()
    {
           $this->conferenceId = 1;
    }

    public function signUpAction(): Action
    {
       return Action::make('signUp')
    ->slideOver()
    ->form([
        TextEntry::make('total_price')
        ->hiddenLabel()
        ->state(function(Get $get){
            
             return '$' . count($get('attendees')) * 500;
        }),
        Repeater::make('attendees')
            ->schema(Attendee::getForm()),
    ])
    ->action(function(array $data) {
        collect($data['attendees'])->each(function($data){
            Attendee::create([
                 'conference_id'=>$this->conferenceId ,
                 'ticket_cost'=>$this->price,
                 'name'=>$data['name'],
                 'email'=>$data['email'],
                 'is_paid'=>true,
                 
                 ]);
        });
       
    })
    ->after(function(){
        Notification::make()->success()->title('Success!')
        ->body(new HtmlString('You have successfully signed up for the conference.'))->send();
    });
    }
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.conference-sign-up-page');
    }
}
