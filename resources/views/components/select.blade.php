@if(Lang::has($label))
	<?php $label = trans($label); ?>
@endif

<div {!! Html::attributes(array_merge(['class' => 'form-group'], $wrapper_attributes)) !!}>
    {{ Form::label($name, $label, array_merge(['class' => 'control-label'], $label_attributes)) }}
    @if($type == 'selectRange')
    {{ Form::selectRange($name, $begin, $end, $selected, array_merge(['class' => 'form-control'], (array) $attributes)) }}
    @elseif($type == 'selectMonth')
    {{ Form::selectMonth($name, $selected, array_merge(['class' => 'form-control'], (array) $attributes), $format) }}
    @else 
    {{ Form::select($name, $values, $selected, array_merge(['class' => 'form-control'], (array) $attributes), (array) $options_attributes, (array) $optiongroups_attributes) }}
    @endif
</div>