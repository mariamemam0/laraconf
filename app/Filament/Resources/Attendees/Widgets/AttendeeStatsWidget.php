<?php

namespace App\Filament\Resources\Attendees\Widgets;

use App\Filament\Resources\Attendees\Pages\ListAttendees;
use App\Models\Attendee;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendeeStatsWidget extends StatsOverviewWidget
{

    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
         return ListAttendees::class;
    }


       protected function getColumns():int
       {
          return 2;
       }

    protected function getStats(): array
    {
        
        return [
              Stat::make('Attendees Count', $this->getPageTableQuery()->count())
              ->description('Total number of attendees')
              ->descriptionIcon('heroicon-o-user-group')
              ->color('success')
              ->chart([1,2,3,4,5,1,1,1]),

            Stat::make('Total Revenue', $this->getPageTableQuery()->sum('ticket_cost')/100),
        ];
    }
}
