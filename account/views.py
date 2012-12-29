from django.http import HttpResponse, HttpResponseRedirect
from django.template import RequestContext, loader
from django.shortcuts import render, render_to_response
from django.core.urlresolvers import reverse
from django.conf import settings
import cgi
import hashlib

consumer = None
client = None

def index(request):
    if not request.session.get('loggedin', None):
        return HttpResponse('no!')
    else:
        return HttpResponse('yo!')
