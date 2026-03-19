<?php

namespace App\Models;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Enums\Region;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Venue extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'region'=> Region::class,
        ];
    }

    public static function getForm():array
    {
        return[
                TextInput::make('name')
                    ->required(),
                TextInput::make('city')
                    ->required(),
                TextInput::make('country')
                    ->required(),
                TextInput::make('postal_code')
                    ->required(),
                Select::make('region')
                ->enum(Region::class)
                ->options(Region::class),
                SpatieMediaLibraryFileUpload::make('images')
                ->collection('venue-images')
                ->multiple()
                ->image(),
            ];
    }
}
