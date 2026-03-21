<?php

namespace App\Filament\Resources\Talks\Pages;

use App\Enums\TalkStatus;
use App\Filament\Resources\Talks\TalkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListTalks extends ListRecords
{
    protected static string $resource = TalkResource::class;

    public function getTabs(): array
    {
        return[
             'all'=> Tab::make('All Talks'),
             'accepted'=> Tab::make('Approved')
             ->modifyQueryUsing(function($query){
                return $query->where('status',TalkStatus::APPROVED);
             }),
             'submitted'=> Tab::make('Submitted')
             ->modifyQueryUsing(function($query){
                return $query->where('status',TalkStatus::APPROVED);
             }),
             'rejected'=> Tab::make('Rejected')
             ->modifyQueryUsing(function($query){
                return $query->where('status',TalkStatus::APPROVED);
             })
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
