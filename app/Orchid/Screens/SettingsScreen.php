<?php

namespace App\Orchid\Screens;

use App\Services\SettingsService;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SettingsScreen extends Screen
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
        return 'Настройки';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Сохранить настройки')->icon('save')->method('update'),
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
                CheckBox::make('email_enabled')
                    ->placeholder('Включить уведомления по E-mail')
                    ->sendTrueOrFalse()
                    ->value($this->settings->email_enabled),
                
                Input::make('email_subject')
                    ->title('Тема письма')
                    ->placeholder('Тема письма')
                    ->value($this->settings->email_subject),

                Group::make([
                    Input::make('email_host')
                        ->title('Хост')
                        ->placeholder('Хост')
                        ->value($this->settings->email_host),

                    Input::make('email_port')
                        ->title('Порт')
                        ->placeholder('Порт')
                        ->value($this->settings->email_port),
                    ]),
                    
                    Group::make([
                        Input::make('email_from_address')
                            ->title('E-mail отправителя')
                            ->help('Адрес, с которого будут отправляться уведомления')
                            ->placeholder('E-mail отправителя')
                            ->value($this->settings->email_username),

                        Input::make('email_username')
                            ->title('Логин от почты')
                            ->placeholder('Логин от почты')
                            ->value($this->settings->email_username),
                        
                        Input::make('email_password')
                            ->type('password')
                            ->title('Пароль от почты')
                            ->placeholder('Пароль от почты')
                            ->value($this->settings->email_password),
                    ]),
                    
                    Group::make([
                        Input::make('email_from_name')
                            ->title('Имя отправителя')
                            ->help('Имя, отображемое в графе "Отправитель"')
                            ->placeholder('Имя отправителя')
                            ->value($this->settings->email_from_name),
                        
                        Input::make('email_to_address')
                            ->title('Отправлять уведомления на')
                            ->help('Адрес E-mail, на который будут отправляться уведомления')
                            ->placeholder('Адрес получателя')
                            ->value($this->settings->email_to_address),
                    ]),
            ]),
        ];
    } //layout
    
    public function update(Request $request)
    {
        $settings = SettingsService::getInstance();
        $settings->setMassive([
            'email_enabled' => $request->email_enabled,
            'email_host' => $request->email_host,
            'email_port' => $request->email_port,
            'email_username' => $request->email_username,
            'email_password' => $request->email_password,
            'email_from_address' => $request->email_from_address,
            'email_from_name' => $request->email_from_name,
            'email_to_address' => $request->email_to_address,
            'email_subject' => $request->email_subject,
        ]);

        Toast::success('Настройки сохранены');
        return redirect()->route('platform.settings');
    } //update
}
