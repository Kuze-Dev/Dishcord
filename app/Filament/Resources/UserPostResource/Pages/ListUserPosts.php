<?php

namespace App\Filament\Resources\UserPostResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\UserPostResource;

class ListUserPosts extends ListRecords
{
    protected static string $resource = UserPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
 
}
