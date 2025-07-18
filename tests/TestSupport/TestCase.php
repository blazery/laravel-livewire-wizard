<?php

namespace Spatie\LivewireWizard\Tests\TestSupport;

use DOMDocument;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\CollectionMacros\CollectionMacroServiceProvider;
use Spatie\LivewireWizard\Components\WizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyHydratedWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\CustomStateStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstHydratedStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondHydratedStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\ThirdStepComponent;
use Spatie\LivewireWizard\WizardServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        View::addNamespace('test', __DIR__ . '/resources/views');

        $this
            ->registerLivewireComponents()
            ->registerLivewireTestMacros();
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            WizardServiceProvider::class,
            CollectionMacroServiceProvider::class,
        ];
    }

    private function registerLivewireComponents(): self
    {
        Livewire::component('wizard', WizardComponent::class);
        Livewire::component('first-step', FirstStepComponent::class);
        Livewire::component('second-step', SecondStepComponent::class);
        Livewire::component('third-step', ThirdStepComponent::class);
        Livewire::component('custom-state-step', CustomStateStepComponent::class);

        Livewire::component('hydrated-wizard', MyHydratedWizardComponent::class);
        Livewire::component('dehydrated-first-step', FirstHydratedStepComponent::class);
        Livewire::component('dehydrated-second-step', SecondHydratedStepComponent::class);
        return $this;
    }

    public function registerLivewireTestMacros(): self
    {
        Testable::macro('jsonContent', function (string $elementId) {
            $document = new DOMDocument();

            $document->loadHTML($this->lastState->getHtml());

            $content = $document->getElementById($elementId)->textContent;

            return json_decode($content, true);
        });

        Testable::macro('htmlContent', function (string $elementId) {
            $document = new DOMDocument();

            $document->preserveWhiteSpace = false;

            $document->loadHTML($this->lastState->getHtml());

            $domNode = $document->getElementById($elementId);

            return Str::of($document->saveHTML($domNode))
                ->replace("\n", "\r\n")
                ->trim()
                ->toString();
        });

        return $this;
    }
}
