from django.conf.urls import patterns, include, url
from home import views as home_views

urlpatterns = patterns('',
    url(r'^$', home_views.index, name='index'),
    url(r'^about/', include('about.urls', namespace="about")),
    url(r'^projects/', include('projects.urls', namespace="projects")),
    url(r'^create/', include('create.urls', namespace="create")),
    url(r'^account/', include('account.urls', namespace="account")),
    url(r'^image/', include('image.urls', namespace="image")),
    url(r'^social/', include('socialregistration.urls', namespace='socialregistration')),
)
