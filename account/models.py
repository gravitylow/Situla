from django.db import models


class User(models.Model):
    username = models.CharField(max_length=64)
    identification = models.CharField(max_length=256)
    ipv4 = models.CharField(max_length=64)
    gravatar = models.CharField(max_length=128)
    alerts = models.IntegerField()
    last_login = models.DateTimeField(blank=True)
    is_active = models.BooleanField()

    def __unicode__(self):
        return self.user

    def is_authenticated():
        return True
