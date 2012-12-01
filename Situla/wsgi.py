import os
import sys

sys.path.append('/usr/situla/Situla')
sys.path.append('/usr/situla')

os.environ["DJANGO_SETTINGS_MODULE"] = "Situla.settings"

import django.core.handlers.wsgi
application = django.core.handlers.wsgi.WSGIHandler()
