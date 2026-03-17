<?php

namespace App\Filament\Resources\Speakers\Schemas;

use App\Models\Speaker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SpeakerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(Speaker::getForm());
    }
}
