<?php

namespace App\Filament\Resources\Speakers;

use App\Enums\TalkStatus;
use App\Filament\Resources\Speakers\Pages\CreateSpeaker;
use App\Filament\Resources\Speakers\Pages\EditSpeaker;
use App\Filament\Resources\Speakers\Pages\ListSpeakers;
use App\Filament\Resources\Speakers\Pages\ViewSpeaker;
use App\Filament\Resources\Speakers\RelationManagers\TalksRelationManager;
use App\Filament\Resources\Speakers\Schemas\SpeakerForm;
use App\Filament\Resources\Speakers\Tables\SpeakersTable;
use App\Models\Speaker;
use BackedEnum;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'speaker';

    public static function form(Schema $schema): Schema
    {
        return SpeakerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpeakersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TalksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSpeakers::route('/'),
            'create' => CreateSpeaker::route('/create'),
            //'edit' => EditSpeaker::route('/{record}/edit'),
            'view'=> ViewSpeaker::route('/{record}'),
        ];
    }
public static function infolist(Schema $shcema):Schema
{
    return $shcema
    ->schema([
            Section::make('personal inforamtion')
            ->columns(3)
            ->schema([
                ImageEntry::make('avatar')->circular(),
                Group::make()
                ->columnSpan(2)
                ->columns(2)
                ->schema([
                TextEntry::make('name'),
                TextEntry::make('email'),
                TextEntry::make('twitter_handle')
                ->label('Twitter')
                ->getStateUsing(function($record){
                     return '@' . $record->twitter_handle;
                })
                ->url(function($record){
                    return 'https://twitter.com'.$record->twitter_handle;
                }),
                TextEntry::make('has_spoken')
                ->getStateUsing(function($record){
                    return $record->talks()->where('status',TalkStatus::APPROVED)->count()
                    >0 ? 'Previous Speaker' : 'Has Not Spoken';

                })->badge()
                ->color(function ($state) {
                                    if($state === 'Previous Speaker') {
                                        return 'success';
                                    }
                                    return 'primary';
                                }),
                ]),
            ])->columnSpanFull(),
           
            Section::make('Other Information')
                ->schema([
                    TextEntry::make('bio')
                        ->extraAttributes(['class' => 'prose dark:prose-invert'])
                        ->html(),
                    TextEntry::make('qualifications')->bulleted(),
                ])
                
    ]);
}

    
     
}
