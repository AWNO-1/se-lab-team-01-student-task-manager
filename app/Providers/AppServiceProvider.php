<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ربط العقد (Interface) بالتنفيذ الفعلي داخل Laravel Service Container (Lab 2 OOP)
        $this->app->bind(
            \App\Contracts\ReportExporterInterface::class,
            \App\Exporters\CsvReportExporter::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
