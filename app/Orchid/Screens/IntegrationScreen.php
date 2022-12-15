<?php

namespace App\Orchid\Screens;

use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;

use App\Services\SettingsService;
use Orchid\Screen\Fields\CheckBox;

class IntegrationScreen extends Screen
{
    public SettingsService $settings;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'settings' => SettingsService::getInstance(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Интеграция с Leads Hunter CRM';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Настройки интеграции";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Сохранить интеграцию')->icon('save')->method('update'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                CheckBox::make('crm_enabled')
                    ->placeholder('Включить интеграцию')
                    ->sendTrueOrFalse()
                    ->value($this->settings->crm_enabled),

                Input::make('project_token')
                        ->title('API-токен проекта')
                        ->placeholder('API-токен проекта')
                        ->value($this->settings->project_token),

                Input::make('crm_url')
                        ->title('Адрес отправления лида на CRM')
                        ->placeholder('Адрес отправления лида на CRM')
                        ->value($this->settings->crm_url),
            ]),
        ];
    } //layout

    public function update(Request $request)
    {
        $settings = SettingsService::getInstance();
        $settings->setMassive([
            'crm_enabled' => $request->crm_enabled,
            'crm_url' => $request->crm_url,
            'project_token' => $request->project_token,
        ]);

        Toast::success('Настройки сохранены');
        return redirect()->route('platform.integration');
    } //update
}
