import os
import sys

os.environ['DJANGO_SETTINGS_MODULE'] = 'Situla.settings'

import django.core.handlers.wsgi
application = django.core.handlers.wsgi.WSGIHandler()

path = '/usr/django'
if path not in sys.path:
    sys.path.append(path)

path = '/usr/django/Situla'
if path not in sys.path:
    sys.path.append(path)