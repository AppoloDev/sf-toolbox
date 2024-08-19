{% extends '_layout/admin.html.twig' %}

{% block title %}Ajout d'un ##ROUTEPATH##{% endblock %}{# TODO: Wording #}

{% block breadcrumb %}
    {# TODO: Wording #}
    <twig:Breadcrumb items="{{[
        {path: path('##AREALOWER##_dashboard'), label: 'Accueil'},
        {path: path('##AREALOWER##_##PREFIX##_list'), label: '##ROUTEPATH##s'},
        {path: null, label: block('title')},
    ] }}"/>
{% endblock %}

{% block body %}
    {% component FormLayout with {
        headerTitle: block('title'),
    } %}
        {% block form_render %}
            {% include 'areas/##AREALOWER##/##PREFIX##/_form.html.twig' %}
        {% endblock %}
    {% endcomponent %}
{% endblock %}
