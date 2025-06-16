<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\UserPost;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\UserPostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserPostResource\RelationManagers;

class UserPostResource extends Resource
{
    protected static ?string $model = UserPost::class;

    protected static ?string $navigationLabel = 'Review Post';

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $navigationGroup = 'System Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()
                ->schema([
                    Card::make()
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('title')
                                    ->label('Post Title')
                                    ->disabled()
                                    ->columnSpan(2),
                                 Textarea::make('body')
                                ->label('Post Content')
                                ->disabled()
                                ->rows(6)
                                ->columnspan(2),
                             ]),    
                         FileUpload::make('image')
                             ->label('Post Image')
                             ->image()
                             ->openable()
                             ->disk('public')
                             ->directory('post_images')
                             ->disabled()
                             ->columnSpan(2),
                     ]),
                ]),
                Section::make('🍽 Recipe Details')
                ->relationship('recipes') // hasOne relation
                ->schema([
                    Card::make()
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('name')
                                    ->label('Recipe Name')
                                    ->disabled(),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->disabled(),
                            ]),
    
                            Repeater::make('ingredients')
                                ->label('🧂 Ingredients')
                                ->relationship('ingredients') // hasMany
                                ->schema([
                                    Grid::make(4)->schema([
                                        TextInput::make('name')->label('Name')->disabled(),
                                        TextInput::make('type')->label('Type')->disabled(),
                                        TextInput::make('quantity')->label('Quantity')->disabled(),
                                        TextInput::make('unit')->label('Unit')->disabled(),
                                    ]),
                                ])
                                ->columns(1)
                                ->collapsible()
                                ->defaultItems(0)
                                ->disabled(),
    
                            Repeater::make('instructions')
                                ->label('👨‍🍳 Steps')
                                ->relationship('instructions') // hasMany
                                ->schema([
                                    Grid::make(1)->schema([
                                        TextInput::make('step_number')->label('Step #')->disabled(),
                                        Textarea::make('step_description')->label('Step Description')->rows(3)->disabled(),
                                    ]),
                                ])
                                ->orderable('step_number')
                                ->collapsible()
                                ->defaultItems(0)
                                ->disabled(),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('body'),
                BooleanColumn::make('_published'),
                BooleanColumn::make('_reviewed'),
            ])
            ->filters([
                TernaryFilter::make('_published'),
                TernaryFilter::make('_reviewed'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserPosts::route('/'),
            'create' => Pages\CreateUserPost::route('/create'),
            'edit' => Pages\EditUserPost::route('/{record}/edit'),
        ];
    }
}
