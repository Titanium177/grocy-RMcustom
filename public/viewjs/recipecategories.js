var recipeCategoriesTable = $('#recipecategories-table').DataTable({
	'order': [[2, 'asc']],
	'columnDefs': [
		{ 'orderable': false, 'targets': 0 },
		{ 'searchable': false, "targets": 0 }
	].concat($.fn.dataTable.defaults.columnDefs)
});
$('#recipecategories-table tbody').removeClass("d-none");
recipeCategoriesTable.columns.adjust().draw();

$("#search").on("keyup", Delay(function()
{
	var value = $(this).val();
	if (value === "all")
	{
		value = "";
	}

	recipeCategoriesTable.search(value).draw();
}, Grocy.FormFocusDelay));

$("#clear-filter-button").on("click", function()
{
	$("#search").val("");
	recipeCategoriesTable.search("").draw();
});

$(document).on('click', '.recipecategory-delete-button', function(e)
{
	var objectName = $(e.currentTarget).attr('data-recipecategory-name');
	var objectId = $(e.currentTarget).attr('data-recipecategory-id');

	bootbox.confirm({
		message: __t('Are you sure you want to delete recipe category "%s"?', objectName),
		closeButton: false,
		buttons: {
			confirm: {
				label: __t('Yes'),
				className: 'btn-success'
			},
			cancel: {
				label: __t('No'),
				className: 'btn-danger'
			}
		},
		callback: function(result)
		{
			if (result === true)
			{
				Grocy.Api.Delete('objects/recipe_categories/' + objectId, {},
					function(result)
					{
						window.location.href = U('/recipecategories');
					},
					function(xhr)
					{
						console.error(xhr);
					}
				);
			}
		}
	});
});
