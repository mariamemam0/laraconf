<?php

namespace App\Filament\Resources\Venues\Schemas;

use App\Enums\Region;
use App\Models\Venue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VenueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(Venue::getForm());
    }
}
