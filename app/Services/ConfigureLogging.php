<?php
namespace App\Services;
use Illuminate\Log\Writer;
use Illuminate\Contracts\Foundation\Application;

class ConfigureLogging
{
    /**
     * 设置应用的Monolog处理程序
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    public function configureHandlers(Application $app, Writer $log)
    {
        $method = 'configure'.ucfirst($app['config']['app.log']).'Handler';

        $this->{$method}($app, $log);
    }

    /**
     * 设置应用single模式下的Monolog处理程序
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureSingleHandler(Application $app, Writer $log)
    {
        $config = $app->make('config');
        $filename = $config->get('diy_log.log_path', '/var/log/nginx/app/system') . '/' . $config->get('diy_log.log_name', 'laravel') . '.log';
        $log->useFiles($filename);
    }

    /**
     * 设置应用daily模式下的Monolog处理程序
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureDailyHandler(Application $app, Writer $log)
    {
        $config = $app->make('config');
        $filename = $config->get('diy_log.log_path', '/var/log/nginx/app/system') . '/' . $config->get('diy_log.log_name', 'laravel') . '.log';
        $log->useDailyFiles(
            $filename,
            $app->make('config')->get('diy_log.log_max_files', 5)
        );
    }

    /**
     * 设置应用syslog模式下的Monolog处理程序
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureSyslogHandler(Application $app, Writer $log)
    {
        $log->useSyslog($app->make('config')->get('diy_log.log_name', 'laravel'));
    }

    /**
     * 设置应用errorlog模式下的Monolog处理程序
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureErrorlogHandler(Application $app, Writer $log)
    {
        $log->useErrorLog();
    }
}