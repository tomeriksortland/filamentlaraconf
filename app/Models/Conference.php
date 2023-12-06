<?php

namespace App\Models;

use App\Enums\Region;
use App\Filament\Resources\SpeakerResource;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conference extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'venue_id' => 'integer',
        'region' => Region::class
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public function attendees() : HasMany
    {
        return $this->hasMany(Attendee::class);
    }

    public static function getForm() : array
    {
        return [
            /*Tabs::make()
                ->columnSpanFull()
            ->tabs([
                Tabs\Tab::make('Conference Details')
                    ->schema([
                    TextInput::make('name')
                        ->label('Conference Name')
                        ->columnSpanFull()
                        ->required()
                        ->maxLength(255),
                    MarkdownEditor::make('description')
                        ->columnSpanFull()
                        ->required(),
                    DateTimePicker::make('start_date')
                        ->native(false)
                        ->required(),
                    DateTimePicker::make('end_date')
                        ->native(false)
                        ->required(),
                ]),
                Tabs\Tab::make('Status')->schema([
                    Toggle::make('is_published')
                        ->columnSpanFull()
                        ->default('true'),
                    Select::make('status')
                        ->columnSpanFull()
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'archived' => 'Archived'
                        ])
                        ->required(),
                ]),
                Tabs\Tab::make('Location')->schema([
                    Select::make('region')
                        ->live()
                        ->enum(Region::class)
                        ->options(Region::class),
                    Select::make('venue_id')
                        ->searchable()
                        ->preload()
                        ->createOptionForm(Venue::getForm())
                        ->editOptionForm(Venue::getForm())
                        ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, Get $get) {
                            return $query->where('region', $get('region'));
                        }),
                ]),
            ]),
            Actions::make([
               Actions\Action::make('star')
                   ->visible(function (string $operation) {
                       if($operation !== 'create') return false;
                       if(! app()->environment('local')) return false;

                       return true;
                   })
                   ->label('Fill with factory data')
                   ->icon('heroicon-m-star')
                   ->action(function ($livewire) {
                       $data = Conference::factory()->make()->toArray();
                       $livewire->form->fill($data);
               })
            ]),*/
            Section::make('Conference Details')
                ->description('Provide some basic information about the conference')
                ->collapsible()
                ->schema([
                    TextInput::make('name')
                        ->label('Conference Name')
                        ->required()
                        ->maxLength(255),
                    MarkdownEditor::make('description')
                        ->required(),
                    DateTimePicker::make('start_date')
                        ->native(false)
                        ->required(),
                    DateTimePicker::make('end_date')
                        ->native(false)
                        ->required(),
                    Fieldset::make('Status')
                    ->schema([
                        Toggle::make('is_published')
                            ->columnSpanFull()
                            ->default('true'),
                        Select::make('status')
                            ->columnSpanFull()
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'archived' => 'Archived'
                            ])
                            ->required(),
                    ])
                ]),
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
                    ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, Get $get) {
                        return $query->where('region', $get('region'));
                    }),
            ]),
            CheckboxList::make('speakers')
            ->relationship('speakers', 'name')
            ->options(
                Speaker::all()->pluck('name', 'id')
            )
        ];
    }
}
