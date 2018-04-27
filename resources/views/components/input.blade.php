@if(Lang::has($label))
	<?php $label = trans($label); ?>
@endif

<div {!! Html::attributes(array_merge(['class' => 'form-group'], $wrapper_attributes)) !!}>
    {{ Form::label($name, $label, array_merge(['class' => 'control-label'], $label_attributes)) }}
    @if(in_array($type, ['password', 'file']))
    	{{ Form::input($type, $name, null, array_merge(['class' => 'form-control'], $attributes)) }}
    @elseif(in_array($type, ['checkbox', 'radio']))
    	<?php $values = (array) $value;?>  
    	@foreach($values as $label => $value) 
	    	<label for="{{ $name}}-{{ $label }}" class="{{ $type }}-inline">
	    		{{ is_numeric($label) ? '' : $label }} {{ 
    			Form::{$type}($name, $value, $selected, array_merge(
    				['class' => 'control-label', 'id' => "{$name}-{$label}"], 
    				$attributes
    			))   
	    	}}  
	    	</label>
    	@endforeach
    @else
    	{{ Form::{$type}($name, $value, array_merge(['class' => 'form-control'], $attributes)) }}
    @endif
</div>