<?php

namespace App\Filament\Resources\Attendees\Widgets;

use App\Filament\Resources\Attendees\Pages\ListAttendees;
use App\Models\Attendee;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AttendeeChartWidget extends ChartWidget
{
    use InteractsWithPageTable;
    protected ?string $heading = 'Attendee Signups';

    protected int | string | array $columnSpan = 'full';
    protected  ?string $maxHeight = '200px';

    public ?string $filter = '3months';

    protected  ?string $pollingInterval = null;

   protected function getFilters(): ?array
    {
        return [
            'week' => 'Last Week',
            'month'=>'Last Month',
            '3month' =>'last 3 Months',
        ];
    }

       protected function getTablePage(): string
    {
        return ListAttendees::class;
    }
    protected function getData(): array
    {
            $filter = $this->filter;
            match($filter){
                'week' => $data = Trend::query($this->getPageTableQuery())
                ->between(
                    start: now()->subWeek(),
                    end: now(),
                )
                ->perDay()
                ->count(),
               'month' => $data = Trend::query($this->getPageTableQuery())
                ->between(
                    start: now()->subMonth(),
                    end: now(),
                )
                ->perDay()
                ->count(),
              '3months' => $data = Trend::query($this->getPageTableQuery())
                ->between(
                    start: now()->subMonths(3),
                    end: now(),
                )
                ->perMonth()
                ->count(),
            };

        

    return [
        'datasets' => [
            [
                'label' => 'Signips',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
