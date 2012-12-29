from django.conf.urls import patterns, url

from account import views

urlpatterns = patterns('',
    url(r'^$', views.index, name='index'),
)
