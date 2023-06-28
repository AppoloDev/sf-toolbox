{% extends '_layout/admin.html.twig' %}

{% block title %}{{ ##ENTITYCAMEL##.id}}{% endblock %}{# TODO: Implements #}

{% block breadcrumb %}
    {% with {items: [
        {path: path('##AREALOWER##_dashboard'), label: 'Accueil'},
        {path: path('##AREALOWER##_##PREFIX##_list'), label: '__ROUTE_PATH__s'},
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
            {% include 'areas/##AREALOWER##/##PREFIX##/_form.html.twig' %}
        {% endblock %}
    {% endcomponent %}
{% endblock %}