<?php

namespace App\Filament\Resources\Attendees\Pages;

use App\Filament\Resources\Attendees\AttendeeResource;
use App\Filament\Resources\Attendees\Widgets\AttendeeChartWidget;
use App\Filament\Resources\Attendees\Widgets\AttendeeStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListAttendees extends ListRecords
{

    use ExposesTableToWidgets;
    protected static string $resource = AttendeeResource::class;



       protected function getHeaderWidgets(): array
    {
        return [
                    
            AttendeeStatsWidget::class,
            AttendeeChartWidget::class,
        
         ];
    }
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
