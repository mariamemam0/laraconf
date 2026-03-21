<?php

namespace App\Filament\Resources\Talks\Tables;

use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use App\Models\Talk;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
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
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Support\Str;

class TalksTable
{
    public static function configure(Table $table): Table
    {

        return $table
            ->persistFiltersInSession()
            ->filtersTriggerAction(function ($action) {
                return $action->button()->label('Filters');
            })
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
                    ->defaultImageUrl(function ($record) {
                        return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($record->speaker->name);
                    }),
                TextColumn::make('speaker.name')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('new_talk'),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(function ($state) {
                        return $state->getColor();
                    }),
                IconColumn::make('length')
                    ->icon(function ($state) {
                        return match ($state) {
                            TalkLength::NORMAL => 'heroicon-o-megaphone',
                            TalkLength::LIGHTNING => 'heroicon-o-bolt',
                            TalkLength::KEYNOTE => 'heroicon-o-key',
                        };
                    })

            ])
            ->filters([
                TernaryFilter::make('new_talk'),
                SelectFilter::make('speaker')
                    ->relationship('speaker', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),

                Filter::make('has_avatar')
                    ->label('show only speakers whithout avatars')
                    ->toggle()
                    ->query(function ($query) {
                        return $query->whereHas('speaker', function (Builder $query) {
                            $query->whereNotNull('avatar');
                        });
                    })
            ])
            ->recordActions([
                EditAction::make()->slideOver(),

                ActionGroup::make([
                    Action::make('approve')
                    ->visible(function($record){
                            return $record->status === (TalkStatus::SUBMITTED);
                    })
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Talk $record) {
                            $record->approve();
                        })->after(function () {
                            Notification::make()->success()->title('This talk was approaved')
                                ->duration(1000)
                                ->body('The speaker has been notified and the talk has been added to the conference schedule.')
                                ->send();
                        }),

                    Action::make('reject')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->visible(function($record){
                             return $record->status === (TalkStatus::REJECTED);
                        })
                        ->requiresConfirmation()
                        ->action(function (Talk $record) {
                            $record->reject();
                        })->after(function () {
                            Notification::make()->danger()->title('This talk was rejected')
                                ->duration(1000)
                                ->body('The speaker has been notified.')
                                ->send();
                        }),
                ]),




            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('approve')
                   ->action(function (Collection $records) {
                            $records->each->approve();
                        }),
                    RestoreBulkAction::make(),
 
                ])

            ])

            ->headerActions([
                Action::make('export')
                ->tooltip('This will export all records visible in the table. Adjust filters to export a subset of records.')
                ->action(function($livewire){
                    ray($livewire->getFilteredTableQuery()->count());
                    ray('Exporting Data');
                })
        ]);
    }
}
