<?php namespace Enovision\MarkdownNotices;


use Event;
use System\Classes\PluginBase;
use Illuminate\Support\Facades\App;
use Enovision\MarkdownNotices\Classes\Helper as MarkdownNoticesHelper;
use Backend\Classes\Controller as BackendController;

class Plugin extends PluginBase
{
    /**
     * @return array|void
     * Replace the notices in the markdown editor of the blog component
     */
    public function boot()
    {
        $this->app->singleton('Enovision\MarkdownNoticesHelper', function ($app) {
            $helper = new MarkdownNoticesHelper();
            return $helper;
        });

        Event::listen('markdown.beforeParse', function ($data) {
            $helper = App::make('Enovision\MarkdownNoticesHelper');
            $data->text = $helper->replaceNotices($data->text);
        });
    }

    /**
     * Backend
     * add css
     */
    public function register()
    {
        BackendController::extend(function ($controller) {
            $controller->addCss('/plugins/enovision/markdownnotices/assets/css/notices.css');
        });
    }

    /**
     * @return array
     * A component to inject the CSS in the frontend
     */
    public function registerComponents()
    {
        return [
            'Enovision\MarkdownNotices\Components\Notices' => 'Notices'
        ];
    }

}
