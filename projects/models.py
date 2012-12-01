from django.db import models
from django.utils.safestring import mark_safe


class Project(models.Model):
    project = models.CharField(max_length=128)
    url = models.CharField(max_length=256)
    description = models.CharField(max_length=5000)
    user = models.CharField(max_length=128)
    created = models.DateTimeField(auto_now_add=True)
    updated = models.DateTimeField(auto_now_add=True)
    rating = models.IntegerField(default=0)
    replies = models.IntegerField(default=0)
    updates = models.IntegerField(default=0)
    logo = models.CharField(max_length=256, default='none')

    def __unicode__(self):
        return 'project ' + self.project + ' by ' + self.user

    @property
    def formatted_rating(self):
        string = "Rating: <span class=\""
        if self.rating >= 0:
            string += "text-success\">"
            if self.rating > 0:
                string += "+"
        else:
            string += "text-failure\">-"
        string += str(self.rating) + "</span>"
        return mark_safe(string)

    @property
    def formatted_description(self):
        bbcode = {
            '[big]': '<big>',
            '[/big]': '</big>',
            '[b]': '<strong>',
            '[/b]': '</strong>',
            '[i]': '<em>',
            '[/i]': '</em>',
            '[u]': '<u>',
            '[/u]': '</u>',
            '[img]': '<img src=\"',
            '[/img]': '\" border=\"0\" />',
            '[code]': '<code>',
            '[/code]': '</code>',
        }
        text = self.description
        for i, j in bbcode.iteritems():
            text = text.replace(i, j)
        return mark_safe(text)


class Comment(models.Model):
    project = models.IntegerField()
    user = models.CharField(max_length=128)
    comment = models.CharField(max_length=5000)
    created = models.DateTimeField('date created')

    def __unicode__(self):
        return 'comment by ' + self.user + ' on project ' + self.project
