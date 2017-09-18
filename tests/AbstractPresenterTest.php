<?php

use PHPUnit\Framework\TestCase;

class AbstractPresenterTest extends TestCase
{
    /** @var \Pustato\Presenter\AbstractPresenter */
    protected $presenter;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        // Test presentable entity. Returns attribute name as value.
        $presentable = new class() implements \Pustato\Presenter\Contracts\PresentableContract {
            /**
             * {@inheritdoc}
             */
            public function getPresentableAttribute(string $name)
            {
                $attrMap = [
                    'presentableAttr1' => 'presentableValue1',
                    'presentableAttr2' => 'presentableValue2',
                ];

                return $attrMap[$name] ?? null;
            }
        };

        // Test presenter with simple config
        $this->presenter = new class($presentable) extends \Pustato\Presenter\AbstractPresenter {
            /**
             * {@inheritdoc}
             */
            protected function attributes(): array
            {
                return [
                    'attribute1' => 'value1',
                    'attribute2' => 'value2',
                    'attribute3' => function () {
                        return uniqid('', true);
                    },
                ];
            }
        };
    }

    /** @test */
    public function presenter_return_custom_attributes()
    {
        $this->assertEquals($this->presenter->attribute1, 'value1');
        $this->assertEquals($this->presenter->attribute2, 'value2');
    }

    /** @test */
    public function presenter_caches_closure_values()
    {
        $value = $this->presenter->attribute3;
        $this->assertEquals($this->presenter->attribute3, $value);
        $this->assertEquals($this->presenter->attribute3, $value);
        $this->assertEquals($this->presenter->attribute3, $value);
    }

    /** @test */
    public function presenter_clears_cache()
    {
        $value = $this->presenter->attribute3;
        $this->assertEquals($this->presenter->attribute3, $value);
        $this->assertEquals($this->presenter->attribute3, $value);

        $this->presenter->refresh();
        $this->assertNotEquals($this->presenter->attribute3, $value);
    }

    /** @test */
    public function presenter_returns_presentable_attributes()
    {
        $this->assertEquals($this->presenter->presentableAttr1, 'presentableValue1');
        $this->assertEquals($this->presenter->presentableAttr2, 'presentableValue2');
    }

    /** @test */
    public function presenter_implements_array_access()
    {
        $this->assertArrayHasKey('presentableAttr1', $this->presenter);
        $this->assertArrayNotHasKey('newAttribute', $this->presenter);

        $this->presenter['newAttribute'] = 'new value';
        $this->assertArrayHasKey('newAttribute', $this->presenter);
        $this->assertEquals($this->presenter['newAttribute'], 'new value');

        unset($this->presenter['newAttribute']);
        $this->assertArrayNotHasKey('newAttribute', $this->presenter);
    }
}
