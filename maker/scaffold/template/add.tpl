{% extends '_layout/admin.html.twig' %}

{% block title %}{{ 'add_an_##ROUTEPATH##'|trans}}{% endblock %}{# TODO: Translation #}

{% block breadcrumb %}
    {# TODO: Translation #}
    <twig:Breadcrumb items="{{[
        {path: path('##AREALOWER##_dashboard'), label: 'home'|trans},
        {path: path('##AREALOWER##_##PREFIX##_list'), label: 'list_of_##ROUTEPATH##s'|trans},
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
