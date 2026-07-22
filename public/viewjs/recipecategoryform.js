$('#save-recipecategory-button').on('click', function(e)
{
	e.preventDefault();

	if (!Grocy.FrontendHelpers.ValidateForm("recipecategory-form", true))
	{
		return;
	}

	var jsonData = $('#recipecategory-form').serializeJSON();
	Grocy.FrontendHelpers.BeginUiBusy("recipecategory-form");

	if (Grocy.EditMode === 'create')
	{
		Grocy.Api.Post('objects/recipe_categories', jsonData,
			function(result)
			{
				if (GetUriParam("embedded") !== undefined)
				{
					window.parent.postMessage(WindowMessageBag("Reload"), Grocy.BaseUrl);
				}
				else
				{
					window.location.href = U('/recipecategories');
				}
			},
			function(xhr)
			{
				Grocy.FrontendHelpers.EndUiBusy("recipecategory-form");
				Grocy.FrontendHelpers.ShowGenericError('Error while saving, probably this item already exists', xhr.response);
			}
		);
	}
	else
	{
		Grocy.Api.Put('objects/recipe_categories/' + Grocy.EditObjectId, jsonData,
			function(result)
			{
				if (GetUriParam("embedded") !== undefined)
				{
					window.parent.postMessage(WindowMessageBag("Reload"), Grocy.BaseUrl);
				}
				else
				{
					window.location.href = U('/recipecategories');
				}
			},
			function(xhr)
			{
				Grocy.FrontendHelpers.EndUiBusy("recipecategory-form");
				Grocy.FrontendHelpers.ShowGenericError('Error while saving, probably this item already exists', xhr.response);
			}
		);
	}
});

$('#recipecategory-form input').keyup(function(event)
{
	Grocy.FrontendHelpers.ValidateForm('recipecategory-form');
});

$('#recipecategory-form input').keydown(function(event)
{
	if (event.keyCode === 13) // Enter
	{
		event.preventDefault();

		if (!Grocy.FrontendHelpers.ValidateForm('recipecategory-form'))
		{
			return false;
		}
		else
		{
			$('#save-recipecategory-button').click();
		}
	}
});

Grocy.FrontendHelpers.ValidateForm('recipecategory-form');
setTimeout(function()
{
	$("#name").focus();
}, Grocy.FormFocusDelay);
