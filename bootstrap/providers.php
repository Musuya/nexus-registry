<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class, // <-- THIS LINE IS CRITICAL
    App\Providers\HorizonServiceProvider::class,
];
