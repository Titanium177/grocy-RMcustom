@extends('layout.default')

@if($mode == 'edit')
@section('title', $__t('Edit recipe category'))
@else
@section('title', $__t('Create recipe category'))
@endif

@section('content')
<div class="row">
	<div class="col">
		<h2 class="title">@yield('title')</h2>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-lg-6 col-12">
		<script>
			Grocy.EditMode = '{{ $mode }}';
		</script>

		@if($mode == 'edit')
		<script>
			Grocy.EditObjectId = {{ $recipeCategory->id }};
		</script>
		@endif

		<form id="recipecategory-form"
			novalidate>

			<div class="form-group">
				<label for="name">{{ $__t('Name') }}</label>
				<input type="text"
					class="form-control"
					required
					id="name"
					name="name"
					value="@if($mode == 'edit'){{ $recipeCategory->name }}@endif">
				<div class="invalid-feedback">{{ $__t('A name is required') }}</div>
			</div>

			@php if($mode == 'edit' && !empty($recipeCategory->sort_number)) { $value = $recipeCategory->sort_number; } else { $value = ''; } @endphp
			@include('components.numberpicker', array(
			'id' => 'sort_number',
			'label' => 'Sort number',
			'min' => 0,
			'value' => $value,
			'isRequired' => false,
			'hint' => $__t('Categories will be ordered by that number')
			))

			<button id="save-recipecategory-button"
				class="btn btn-success">{{ $__t('Save') }}</button>

		</form>
	</div>
</div>
@stop
