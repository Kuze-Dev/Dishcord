<?php

namespace App\Filament\Resources\UserPostResource\Pages;

use Filament\Actions;
use Filament\Actions\EditAction;
use Filament\Pages\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\UserPostResource;

class EditUserPost extends EditRecord
{
    protected static string $resource = UserPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('markReviewed')
                ->label('✅ Approve & Publish Post')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->modalHeading('Approve & Publish this Post?')
                ->modalSubheading('This will mark the post as reviewed and make it visible to users.')
                ->modalButton('Yes, Approve and Publish')
                ->action(function () {
                    $this->record->update([
                        '_reviewed' => true,
                        '_published' => true,
                    ]);
                })
                ->successNotificationTitle('Post successfully reviewed and published.')
                ->visible(fn () => !$this->record->_reviewed),

                Action::make('RevertReview')
                ->label('Revert Review')
                ->color('info')
                ->icon('heroicon-o-arrow-uturn-left')
                ->requiresConfirmation()
                ->modalHeading('Revert Review of this Post?')
                ->modalSubheading('This will mark the post as reviewed and make it visible to users.')
                ->modalButton('Yes,Revert Review')
                ->action(function () {
                    $this->record->update([
                        '_reviewed' => false,
                        '_published' => false,
                    ]);
                })
                ->successNotificationTitle('Post successfully reviewed and published.')
                ->visible(fn () => $this->record->_reviewed),
    
            Actions\DeleteAction::make(),
        ];
    }
    

    protected function getSavedNotificationTitle(): ?string
    {

        return 'User updated';

    }
}
