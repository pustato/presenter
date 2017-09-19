# Simple View Presenter

The tool to separate logic of models and views.

## Install

Require this package with composer

```bash
composer require pustato/presenter
```

## Usage

Implement `Pustato\Presenter\Contracts\PresentableContract` interface.

```php
<?php
use Pustato\Presenter\Contracts\PresentableContract;

class SomeModel implements PresentableContract {

    /** @var int */
    public $id;

    /** @var string */
    public $firstName;

    /** @var string */
    public $lastName;
    
    /** @var \DateTime */
    public $birthDate;

    public function getPresentableAttribute(string $name)
    {
        return property_exists($this, $name) ? $this->$name : null;
    }
}
```

Make presenter class.

```php
<?php
use Pustato\Presenter\AbstractPresenter;
use Pustato\Presenter\Contracts\PresentableContract;

class SomeModelPresenter extends AbstractPresenter {

    protected function attributes(PresentableContract $presentable): array
    {
        return [
            'fullName' => $presentable->firstName.' '.$presentable->lastName,
            
            // callable will be calculated at first call and cached.
            'age' => function($presentable) {
                return (int) $presentable
                    ->birthDate
                    ->diff(new \DateTime('now'))
                    ->y;
            }
        ];
    }

}
```

Create presenter instance and use in views.

```php
$presenterInstance = new SomeModelPresenter($modelInstance)
```

```php
<p><strong>ID</strong>: <?= $presenterInstance->id ?></p>
<p><strong>Full name</strong>: <?= $presenterInstance->fullName ?></p>
<p><strong>Age</strong>: <?= $presenterInstance->age ?></p>
```