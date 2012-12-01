from django.http import HttpResponse, HttpResponseRedirect
from django.template import RequestContext, loader
from django.shortcuts import render
from django.core.urlresolvers import reverse
from oauth2app.authenticate import Authenticator, AuthenticationException
from django.http import HttpResponse


def index(request):
    if not request.session.get('loggedin', None):
        return HttpResponseRedirect(reverse('account:login'))
    else:
        return HttpResponse('yo!')


def login(request):
    return render(request, 'account/login/index.html', RequestContext(request))
