from django.http import HttpResponse, HttpResponseRedirect
from django.template import Context, loader
from django.core.urlresolvers import reverse
from django.shortcuts import render
from create.forms import CreateForm
from projects.models import Project


def index(request):
    if request.method == 'POST':
        form = CreateForm(request.POST)
        if form.is_valid():
            project = Project(
                project=form.cleaned_data['project_name'],
                url=form.cleaned_data['project_url'],
                description=form.cleaned_data['project_description'],
                user='test',
            )
            project.save()
            return HttpResponseRedirect(reverse(
                'projects:project',
                args=(
                    project.id,
                )
            ))

    else:
        form = CreateForm()
    return render(request, 'create/index.html', {'form': form,})
