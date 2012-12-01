from django.conf.urls import patterns, url

from about import views

urlpatterns = patterns('',
    url(r'^$', views.index, name='index'),
    url(r'^checklist', views.checklist, name='checklist'),
)
