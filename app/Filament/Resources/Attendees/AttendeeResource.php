<?php

namespace App\Filament\Resources\Attendees;

use App\Filament\Resources\Attendees\Pages\CreateAttendee;
use App\Filament\Resources\Attendees\Pages\EditAttendee;
use App\Filament\Resources\Attendees\Pages\ListAttendees;
use App\Filament\Resources\Attendees\Schemas\AttendeeForm;
use App\Filament\Resources\Attendees\Tables\AttendeesTable;
use App\Filament\Resources\Attendees\Widgets\AttendeeChartWidget;
use App\Filament\Resources\Attendees\Widgets\AttendeeStatsWidget;
use App\Models\Attendee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;


class AttendeeResource extends Resource
{
    protected static ?string $model = Attendee::class;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon:: UserGroup;
   protected static UnitEnum|string|null $navigationGroup = 'First Group';

    protected static ?string $recordTitleAttribute = 'name';
     

  public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Conference' => $record->conference->name,
        ];
    }
   

    public static function getNavigationBadge(): ?string
    {
        return Attendee::count();
    }

     public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }



    public static function form(Schema $schema): Schema
    {
        return AttendeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendeesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }



       public static function getWidgets(): array
    {
        return [
            AttendeeStatsWidget::class,
            AttendeeChartWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendees::route('/'),
            'create' => CreateAttendee::route('/create'),
            'edit' => EditAttendee::route('/{record}/edit'),
        ];
    }
}
