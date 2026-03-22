<?php

namespace App\Filament\Resources\Talks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TalkForm
{
    public static function configure($speakerId = null): array
    {
        return [
                TextInput::make('title')
                    ->required(),
                Textarea::make('abstract')
                    ->required()
                    ->columnSpanFull(),
                Select::make('speaker_id')
                    ->hidden(fn() => $speakerId !== null)
                    ->relationship('speaker', 'name')
                    ->required(),
            ];
    }
}
