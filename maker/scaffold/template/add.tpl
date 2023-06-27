{% extends '_layout/admin.html.twig' %}

{% block title %}Ajout d'un __ROUTE_PATH__{% endblock %}{# TODO: Wording #}

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
    {% component form_layout with {
        headerTitle: block('title'),
    } %}
        {% block form_render %}
            {% include 'areas/__LOWER_AREA__/__PREFIX__/_form.html.twig' %}
        {% endblock %}
    {% endcomponent %}
{% endblock %}
