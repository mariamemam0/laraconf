<?php

namespace App\Filament\Resources\Talks\Tables;

use App\Enums\TalkLength;
use App\Models\Talk;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Icon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TalksTable
{
    public static function configure(Table $table): Table
    {
        
        return $table
            ->persistFiltersInSession()
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                ->description(function (Talk $record) {
                    return Str::limit($record->abstract, 40);
                }),
                    
                 ImageColumn::make('speaker.avatar')
                 ->circular()
                 ->label('Speaker Avatar')
                 ->defaultImageUrl(function($record){
                      return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($record->speaker->name);
                 }),
                TextColumn::make('speaker.name')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('new_talk'),
                TextColumn::make('status')
                ->badge()
                ->sortable()
                ->color(function($state){
                        return $state->getColor();
                }),
                IconColumn::make('length')
                ->icon(function($state){
                    return match($state){
                        TalkLength::NORMAL => 'heroicon-o-megaphone',
                        TalkLength::LIGHTNING => 'heroicon-o-bolt',
                        TalkLength::KEYNOTE => 'heroicon-o-key',
                    };
                })
               
            ])
            ->filters([
                TernaryFilter::make('new_talk'),
                SelectFilter::make('speaker')
                ->relationship('speaker','name')
                ->multiple()
                ->searchable()
                ->preload(),

               Filter::make('has_avatar')
               ->label('show only speakers whithout avatars')
               ->toggle()
               ->query(function($query){
                return $query->whereHas('speaker',function(Builder $query){
                     $query->whereNotNull('avatar');
                });
               })
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ])
             

            ]) ;
    }
}
