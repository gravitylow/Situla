from django.http import HttpResponseRedirect
from django.template import RequestContext
from django.shortcuts import render, get_object_or_404
from django.core.urlresolvers import reverse
from projects.models import Project, Comment


def index(request):
    # Obtain project list
    project_list = Project.objects.order_by('-created')
    context = RequestContext(request, {
        'project_list': project_list,
    })
    return render(request, 'projects/index.html', context)


def project(request, project_id):
    # Obtain project information
    project = get_object_or_404(Project, pk=project_id)
    comment_list = Comment.objects.filter(project=project_id)

    # Setup Context
    info = {
        'project': project,
        'comment_list': comment_list,
    }

    # Votes
    if 'compliant' in request.POST or 'not_compliant' in request.POST:
        info['vote_change'] = 'true'
        return HttpResponseRedirect(reverse(
            'projects:project',
            args=(
                project_id,
            )
        ))
    context = RequestContext(request, info)
    return render(request, 'projects/project.html', context)
