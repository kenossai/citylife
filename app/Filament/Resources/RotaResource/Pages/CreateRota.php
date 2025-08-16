<?php

namespace App\Filament\Resources\RotaResource\Pages;

use App\Filament\Resources\RotaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRota extends CreateRecord
{
    protected static string $resource = RotaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = \Illuminate\Support\Facades\Auth::id();
        $data['schedule_data'] = []; // Initialize empty schedule

        // Ensure departments is set - fallback to all departments if empty
        if (empty($data['departments'])) {
            $data['departments'] = ['worship', 'technical', 'preacher'];
        }

        return $data;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCreateAndGenerateFormAction(),
            ...(static::canCreateAnother() ? [$this->getCreateAnotherFormAction()] : []),
            $this->getCancelFormAction(),
        ];
    }

    protected function getCreateAndGenerateFormAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('createAndGenerate')
            ->label('Create & Auto Generate')
            ->icon('heroicon-o-sparkles')
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading('Create and Auto Generate Rota')
            ->modalDescription('This will create the rota and immediately generate member assignments for all Sundays.')
            ->modalSubmitActionLabel('Create & Generate')
            ->action(function () {
                try {
                    // Get form data - this works better in form actions
                    $data = $this->form->getState();

                    // Create the rota first
                    $data = $this->mutateFormDataBeforeCreate($data);
                    $rota = $this->getModel()::create($data);

                    // Then auto-generate the schedule
                    $generator = new \App\Services\RotaGeneratorService();
                    $schedule = $generator->generateWithRandomization($rota);
                    $rota->update(['schedule_data' => $schedule]);

                    $scheduleCount = count($schedule);

                    \Filament\Notifications\Notification::make()
                        ->title('Rota Created and Generated!')
                        ->success()
                        ->body("The rota '{$rota->title}' has been created and automatically populated with {$scheduleCount} schedule entries.")
                        ->send();

                    return redirect()->route('filament.admin.resources.rotas.index');

                } catch (\Illuminate\Validation\ValidationException $e) {
                    // Re-throw validation exceptions so the form can handle them
                    throw $e;
                } catch (\Exception $e) {
                    \Filament\Notifications\Notification::make()
                        ->title('Error Creating Rota')
                        ->danger()
                        ->body('Failed to create and generate rota: ' . $e->getMessage())
                        ->send();
                }
            });
    }
}
