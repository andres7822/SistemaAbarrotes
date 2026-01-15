<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class FormElement extends Component
{
    public $id, $label, $type, $value, $accept, $colSize, $required, $params, $placeholder, $optionValue,
        $optionContent, $optionContentExtra, $optionsExtraFirst, $optionsExtraEnd, $classDiv, $idDiv, $focused,
        $readonly, $classForm, $dataOptions;

    /**
     * @param $id
     * @param null $label
     * @param string $type
     * @param null $value
     * @param string $accept
     * @param int $colSize
     * @param bool $required
     * @param object|null $params
     * @param string $placeholder
     * @param string $optionValue
     * @param string $optionContent
     * @param string $optionContentExtra
     * @param array $optionsExtraFirst
     * @param array $optionsExtraEnd
     * @param string $classDiv
     * @param string $idDiv
     * @param bool $focused
     * @param bool $readonly
     * @param string $classForm
     * @param string $dataOptions
     */
    public function __construct($id, $label = null, string $type = 'text', $value = null, string $accept = '', int $colSize = 12,
                                bool $required = false, object $params = null, string $placeholder = '',
                                string $optionValue = 'id', string $optionContent = '', string $optionContentExtra = '',
                                array $optionsExtraFirst = [], array $optionsExtraEnd = [], string $classDiv = '',
                                string $idDiv = '', bool $focused = false, bool $readonly = false,
                                string $classForm = '', string $dataOptions = '')
    {
        $this->id = $id;
        $this->label = $label ?? Str::of($id)->replace(['_id', '_'], ['', ' '])->title();
        $this->type = $type;
        $this->value = old($id, $value);
        $this->accept = $accept;
        $this->colSize = $colSize;
        $this->required = $required;
        $this->params = $params;
        $this->placeholder = $placeholder;
        $this->optionValue = $optionValue;
        $this->optionContent = $optionContent;
        $this->optionContentExtra = $optionContentExtra;
        $this->optionsExtraFirst = $optionsExtraFirst;
        $this->optionsExtraEnd = $optionsExtraEnd;
        $this->classDiv = $classDiv;
        $this->idDiv = $idDiv;
        $this->focused = $focused;
        $this->readonly = $readonly;
        $this->classForm = $classForm;
        $this->dataOptions = $dataOptions;
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
