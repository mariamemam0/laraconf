<?php

namespace App\Filament\Resources\Conferences\Schemas;

use App\Enums\Region;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ConferenceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Conference Name')
                    ->default('My Conference')
                    ->helperText('This the name of the context')
                    ->maxLength(60)
                    ->required(),
                RichEditor::make('description')
                    ->required()
                    ->disableToolbarButtons(['italic'])
                    ,
                DateTimePicker::make('start_date')
                    ->required(),
                DateTimePicker::make('end_date')
                    ->required(),
                    Checkbox::make('is_published')
                      ->default(true),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])
                    ->required(),


                 Select::make('region')
                 ->live()
                ->enum(Region::class)
                ->options(Region::class),
                Select::make('venue_id')
                    ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query,$get) {
                        ray($get('region'));
                        return $query->where('region', $get('region'));
                    })
            ]);
    }
}
