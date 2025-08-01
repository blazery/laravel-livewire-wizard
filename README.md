<div align="left">
<h1>Build wizards using Livewire</h1>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/blazery/laravel-livewire-wizard.svg?style=flat-square)](https://packagist.org/packages/blazery/laravel-livewire-wizard)
[![Total Downloads](https://img.shields.io/packagist/dt/blazery/laravel-livewire-wizard.svg?style=flat-square)](https://packagist.org/packages/blazery/laravel-livewire-wizard)
    
</div>

This package is a fork of [Spaties larave-livewire-wizard package](https://github.com/spatie/laravel-livewire-wizard), and tries to address and issue I ran into while developing using the package. Should you have encountered the same issue, migrating to this package should be swift, and a near drop in replacement.

## The issue
I like what Livewire offers: Forms, Models, and Enums, all easily accessible in Livewire/Components. Unfortunately, using these in a StepComponent will break the state management done by the Wizard. Navigating back to a previous step that uses one of these data types will likely throw an Exception.

```php
Cannot assign string to property \StepComponent::$likesCoffee of type \Enums\LikesCoffeeEnum;
``` 
or 
```php
Cannot assign array to property \StepComponent::$form of type \Livewire\Form;
```

This is because these objects don't retain type while traversing the Livewire event system. By default, events get serialized to JSON, and deserialized by the wizard again. This flow of events through the frontend results in data arriving with array or string types, instead of Forms, Arrays, or other synthesizables. This change uses Livewire/Synthesizers, an existing Livewire feature, to process these events instead. The end result, Forms, Models, Enums, and all other Synthesizable data types available in StepComponents.ult, Forms, Models, Enums and all other Synthesizable data types available in StepComponents.

## Documentation

### Migrating to this package
This package ships with two new classes the replace the old ones. 
```diff
- use Spatie\LivewireWizard\Components\WizardComponent;
+ use Spatie\LivewireWizard\Components\HydratedWizardComponent;

- class MyWizardComponent extends WizardComponent
+ class MyWizardComponent extends HydratedWizardComponent
{

}
```

```diff
- use Spatie\LivewireWizard\Components\StepComponent;
+ use Spatie\LivewireWizard\Components\DehydratedStepComponent;

- class MyStepComponent extends StepComponent
+ class MyStepComponent extends DehydratedStepComponent
{

}
```
Changing these classes is all it takes for your steps and wizard to support a wider variety of datatypes. For backwards compatibility reasons, the original components are still available. StepComponents that don't require Forms or Models can still extend `Spatie\LivewireWizard\Components\StepComponent`, skipping the dehydration of events. This is compatible with the new `HydratedWizardComponent`, so mixing `StepComponents` and `DehydratedStepComponent` in a single Wizard is allowed.

### Alternative method
The changes made for this new feature are also available as traits. Adding these to existing Wizards and Steps should have the same result.  

See, `Spatie\LivewireWizard\Components\Concerns\WizardHydratesState` and `Spatie\LivewireWizard\Components\Concerns\DehydratedStepComponent` for more info.

```diff
use Spatie\LivewireWizard\Components\WizardComponent;
+ use Spatie\LivewireWizard\Components\Concerns\WizardHydratesState;

class MyWizardComponent extends WizardComponent
{
+   use WizardHydratesState;

}
```

```diff
  use Spatie\LivewireWizard\Components\StepComponent;
+ use Spatie\LivewireWizard\Components\Concerns\DehydratedStepComponent;

class MyStepComponent extends StepComponent
{
+    use DehydratedStepComponent;

}
```


## Testing

```bash
composer test
```

## Credits
Credits to the original developers
- [Freek Van der Herten](https://github.com/freekmurze)
- [Rias Van der Veken](https://github.com/riasvdv)
- [All Contributors](https://github.com/spatie/laravel-livewire-wizard/graphs/contributors)

And credits to the developers over at [Dolphiq](https://dolphiq.nl/), for providing feedback on this.

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
