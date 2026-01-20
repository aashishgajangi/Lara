<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Site Settings</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage your website content, layout, and appearance</p>
                </div>
            </div>
            
            <form wire:submit.prevent="save">
                {{ $this->form }}
                
                <div class="flex justify-end mt-6">
                    <x-filament::button type="submit" color="success">
                        <x-heroicon-o-check class="w-4 h-4 mr-2" />
                        Save Settings
                    </x-filament::button>
                </div>
            </form>
        </div>
        
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start">
                <x-heroicon-o-information-circle class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" />
                <div class="text-sm">
                    <p class="text-blue-800 dark:text-blue-200 font-medium">Quick Tips:</p>
                    <ul class="mt-2 text-blue-700 dark:text-blue-300 space-y-1">
                        <li>• Changes are saved immediately to your database</li>
                        <li>• Hero section changes appear on your homepage</li>
                        <li>• Navigation changes update your main menu</li>
                        <li>• Use relative URLs (like /about) for internal links</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
