<?php

namespace App\Filament\Resources\Attendees\Schemas;

use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;

use Filament\Schemas\Schema;

class AttendeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Shout::make('warn-price')
                    ->visible(function (Get $get) {
                        return $get('ticket_cost') > 500;
                    })
                    ->columnSpanFull()
                    ->type('warning')
                    ->content(function (Get $get) {
                        $price = $get('ticket_cost');
                        return 'This is ' . $price - 500 . ' more than the average ticket price';
                    }),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('ticket_cost')
                    ->lazy()
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Toggle::make('is_paid')
                    ->required(),
                TextInput::make('conference_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
