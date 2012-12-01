from django import forms


# Forms
class CreateForm(forms.Form):
    project_name = forms.CharField()
    project_url = forms.URLField()
    project_description = forms.CharField(max_length=5000)
