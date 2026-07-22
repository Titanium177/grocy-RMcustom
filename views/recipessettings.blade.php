@php require_frontend_packages(['datatables']); @endphp

@extends('layout.default')

@section('title', $__t('Recipes settings'))

@section('content')
<div class="row">
	<div class="col">
		<h2 class="title">@yield('title')</h2>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col">
		<h3>{{ $__t('Recipe categories') }}</h3>
	</div>
</div>

<div class="row">
	<div class="col text-right mb-3">
		<a class="btn btn-primary show-as-dialog-link"
			href="{{ $U('/recipecategory/new?embedded') }}">
			{{ $__t('Add') }}
		</a>
	</div>
</div>

<div class="row collapse d-md-flex"
	id="table-filter-row">
	<div class="col-12 col-md-6 col-xl-3">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text"><i class="fa-solid fa-search"></i></span>
			</div>
			<input type="text"
				id="search"
				class="form-control"
				placeholder="{{ $__t('Search') }}">
		</div>
	</div>
	<div class="col">
		<div class="float-right">
			<button id="clear-filter-button"
				class="btn btn-sm btn-outline-info"
				data-toggle="tooltip"
				title="{{ $__t('Clear filter') }}">
				<i class="fa-solid fa-filter-circle-xmark"></i>
			</button>
		</div>
	</div>
</div>

<div class="row mb-4">
	<div class="col">
		<table id="recipecategories-table"
			class="table table-sm table-striped nowrap w-100">
			<thead>
				<tr>
					<th class="border-right"><a class="text-muted change-table-columns-visibility-button"
							data-toggle="tooltip"
							title="{{ $__t('Table options') }}"
							data-table-selector="#recipecategories-table"
							href="#"><i class="fa-solid fa-eye"></i></a>
					</th>
					<th>{{ $__t('Name') }}</th>
					<th>{{ $__t('Sort number') }}</th>
				</tr>
			</thead>
			<tbody class="d-none">
				@foreach($recipeCategories as $recipeCategory)
				<tr>
					<td class="fit-content border-right">
						<a class="btn btn-info btn-sm show-as-dialog-link"
							href="{{ $U('/recipecategory/') }}{{ $recipeCategory->id }}?embedded"
							data-toggle="tooltip"
							title="{{ $__t('Edit this item') }}">
							<i class="fa-solid fa-edit"></i>
						</a>
						<a class="btn btn-danger btn-sm recipecategory-delete-button"
							href="#"
							data-recipecategory-id="{{ $recipeCategory->id }}"
							data-recipecategory-name="{{ $recipeCategory->name }}"
							data-toggle="tooltip"
							title="{{ $__t('Delete this item') }}">
							<i class="fa-solid fa-trash"></i>
						</a>
					</td>
					<td>
						{{ $recipeCategory->name }}
					</td>
					<td>
						{{ $recipeCategory->sort_number }}
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

<hr class="my-4">

<div class="row">
	<div class="col">
		<h3>{{ $__t('Display settings') }}</h3>
	</div>
</div>

<div class="row">
	<div class="col-lg-4 col-md-8 col-12">
		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="recipes_show_list_side_by_side"
					data-setting-key="recipes_show_list_side_by_side">
				<label class="form-check-label custom-control-label"
					for="recipes_show_list_side_by_side">
					{{ $__t('Show the recipe list and the recipe side by side') }}
				</label>
			</div>
		</div>

		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="recipes_show_ingredient_checkbox"
					data-setting-key="recipes_show_ingredient_checkbox">
				<label class="form-check-label custom-control-label"
					for="recipes_show_ingredient_checkbox">
					{{ $__t('Show a little checkbox next to each ingredient to mark it as done') }}
					<i class="fa-solid fa-question-circle text-muted"
						data-toggle="tooltip"
						data-trigger="hover click"
						title="{{ $__t('The ingredient is crossed out when clicked, the status is not saved, means reset when the page is reloaded') }}"></i>
				</label>
			</div>
		</div>

		<h4 class="mt-5">{{ $__t('Recipe card') }}</h4>
		<div class="form-group">
			<div class="custom-control custom-checkbox">
				<input type="checkbox"
					class="form-check-input custom-control-input user-setting-control"
					id="recipe_ingredients_group_by_product_group"
					data-setting-key="recipe_ingredients_group_by_product_group">
				<label class="form-check-label custom-control-label"
					for="recipe_ingredients_group_by_product_group">
					{{ $__t('Group ingredients by their product group') }}
				</label>
			</div>
		</div>

		<a href="{{ $U('/recipes') }}"
			class="btn btn-success">{{ $__t('OK') }}</a>
	</div>
</div>
@stop
