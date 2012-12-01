from django.shortcuts import render


def index(request):
    return render(request, 'about/index.html')


def checklist(request):
    return render(request, 'about/checklist.html')
