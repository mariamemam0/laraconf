<?php

namespace App\Models;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'qualifications'=>'array',
        ];
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }
    public static function getForm():array
    { 
         return [
                TextInput::make('name')
                    ->required(),
                FileUpload::make('avatar')
                ->avatar()
                ->imageEditor()
                ->maxSize(1024 * 1024 * 10),   
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Textarea::make('bio')
                    
                    ->columnSpanFull(),
                TextInput::make('twitter_handle')
                  ,

                CheckboxList::make('qualifications')  
                ->searchable()
                ->columnSpanFull()
                ->options([
                        'business-leader' => 'Business Leader',
                        'charisma' => 'Charismatic Speaker',
                        'first-time' => 'First Time Speaker',
                        'hometown-hero' => 'Hometown Hero',
                        'humanitarian' => 'Works in Humanitarian Field',
                        'laracasts-contributor' => 'Laracasts Contributor',
                        'twitter-influencer' => 'Large Twitter Following',
                        'youtube-influencer' => 'Large YouTube Following',
                        'open-source' => 'Open Source Creator / Maintainer',
                        'unique-perspective' => 'Unique Perspective'
                    ])
                    ->descriptions([ 'business-leader' => 'Business Leader',
                        'charisma' => 'Charismatic Speaker'])
                        
                    ->columns(3) ,
            ];
    }
}
