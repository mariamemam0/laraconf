<?php

namespace App\Filament\Resources\Conferences\Schemas;

use App\Enums\Region;
use App\Models\Speaker;
use App\Models\Venue;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
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
            Section::make('Conference Details')
            ->collapsed()
                 ->description('Provide some basic information about the conference.')
                ->icon('heroicon-o-information-circle')
                ->columns(['md'=>2,'lg'=>3])
            ->schema([
                TextInput::make('name')
                    ->columnSpanFull()
                    ->label('Conference Name')
                    ->default('My Conference')
                    ->required()
                    ->maxLength(60),
                MarkdownEditor::make('description')
                    ->columnSpanFull()
                    ->required(),
                DateTimePicker::make('start_date')
                    ->native(false)
                    ->required(),
                DateTimePicker::make('end_date')
                    ->native(false)
                    ->required(),
                Fieldset::make('Status')
                    ->columns(1)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'archived' => 'Archived',
                            ])
                            ->required(),
                        Toggle::make('is_published')
                            ->default(true),
                    ]),
            ])->columnSpanFull(),

            Section::make('Location')
                ->columns(2)
            ->schema([
                Select::make('region')
                    ->live()
                    ->enum(Region::class)
                    ->options(Region::class),
                Select::make('venue_id')
                    ->searchable()
                    ->preload()
                    ->createOptionForm(Venue::getForm())
                    ->editOptionForm(Venue::getForm())
                    ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query,  $get) {
                        return $query->where('region', $get('region'));
                    }),
            ])->columnSpanFull(),
        ]);
    }
}
