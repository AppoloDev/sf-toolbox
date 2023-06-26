{% extends '_layout/admin.html.twig' %}

{% block title %}{{ __ENTITYCAMEL__.id}}{# TODO: Implement #}{% endblock %}

{% block breadcrumb %}
    {% with {items: [
        {path: path('__LOWER_AREA___dashboard'), label: 'Accueil'},
        {path: path('__LOWER_AREA_____PREFIX___list'), label: '__ROUTE_PATH__s'},
        {path: null, label: block('title')},
    ]} %}
        {{ block('breadcrumb', '_shared/blocks/breadcrumb.html.twig') }}
    {% endwith %}
{% endblock %}

{% block body %}
    {% embed '_shared/_layout/form.html.twig' with {
        title: block('title'),
    } %}
        {% block formRender %}
            {% include 'areas/__LOWER_AREA__/__PREFIX__/_form.html.twig' %}
        {% endblock %}
    {% endembed %}
{% endblock %}