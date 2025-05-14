<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StockBadge extends Component
{
    /**
     * The stock amount.
     *
     * @var int
     */
    public $stock;

    /**
     * Create a new component instance.
     *
     * @param  int  $stock
     * @return void
     */
    public function __construct($stock)
    {
        $this->stock = $stock;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.stock-badge');
    }

    /**
     * Get the color for the stock badge.
     *
     * @return string
     */
    public function color()
    {
        if ($this->stock <= 0) {
            return 'red';
        } elseif ($this->stock <= 10) {
            return 'yellow';
        } else {
            return 'green';
        }
    }

    /**
     * Determine if the stock is low.
     *
     * @return bool
     */
    public function isLow()
    {
        return $this->stock > 0 && $this->stock <= 10;
    }

    /**
     * Determine if the stock is out.
     *
     * @return bool
     */
    public function isOut()
    {
        return $this->stock <= 0;
    }
}