<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class FormElement extends Component
{
    public $id, $label, $type, $value, $colSize, $required, $params, $placeholder, $optionValue, $optionContent,
        $optionContentExtra, $optionsExtra, $classDiv, $idDiv, $focused;

    /**
     * @param $id
     * @param null $label
     * @param string $type
     * @param null $value
     * @param int $colSize
     * @param bool $required
     * @param object|null $params
     * @param string $placeholder
     * @param string $optionValue
     * @param string $optionContent
     * @param string $optionContentExtra
     * @param array $optionsExtra
     * @param string $classDiv
     * @param string $idDiv
     * @param bool $focused
     */
    public function __construct($id, $label = null, string $type = 'text', $value = null, int $colSize = 12,
                                bool $required = false, object $params = null, string $placeholder = '',
                                string $optionValue = 'id', string $optionContent = '', string $optionContentExtra = '',
                                array $optionsExtra = [], string $classDiv = '', string $idDiv = '', bool $focused = false)
    {
        $this->id = $id;
        $this->label = $label ?? Str::of($id)->replace(['_id', '_'], ['', ' '])->title();
        $this->type = $type;
        $this->value = old($id, $value);
        $this->colSize = $colSize;
        $this->required = $required;
        $this->params = $params;
        $this->placeholder = $placeholder;
        $this->optionValue = $optionValue;
        $this->optionContent = $optionContent;
        $this->optionContentExtra = $optionContentExtra;
        $this->optionsExtra = $optionsExtra;
        $this->classDiv = $classDiv;
        $this->idDiv = $idDiv;
        $this->focused = $focused;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-element');
    }
}
