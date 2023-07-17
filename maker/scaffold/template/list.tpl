{% extends '_layout/admin.html.twig' %}

{% block title %}Liste des ##ROUTEPATH##s{% endblock %}{# TODO: Wording #}

{% block breadcrumb %}
    {{ component('breadcrumb', {items: [
        {path: path('##AREALOWER##_dashboard'), label: 'Accueil'},
        {path: null, label: block('title')},
    ]}) }}
{% endblock %}

{% block body %}
    {% component table_list with {
        headerTitle: block('title'),
        tableColumns: [
            knp_pagination_sortable(pagination, 'ID', '##ENTITYLOWER##.id'), {# TODO: Implements #}
            ''
        ],
        pagination: pagination,
        emptyText: 'Essayez d\'ajouter un nouvel ##ROUTEPATH##s.', {# TODO: Wording #}
    } %}
        {% block header_actions %}
            <div class="flex gap-4 items-center">
                {% if is_granted('##AREALOWER##_##PREFIX##_export') %}
                    {{ component('button_link', {
                        link: path('##AREALOWER##_##PREFIX##_export', {q: app.request.query.get('q')}),
                        label: 'Exporter',
                        color: themeColor,
                        mode: 'ghost',
                        icon: source('@SFToolbox/icons/download.svg'),
                    }) }}
                {% endif %}

                {% if is_granted('##AREALOWER##_##PREFIX##_add') %}
                    {{ component('button_link', {
                        link: path('##AREALOWER##_##PREFIX##_add'),
                        label: 'Ajouter',
                        color: themeColor,
                        icon: source('@SFToolbox/icons/plus.svg')
                    }) }}
                {% endif %}
            </div>
        {% endblock %}

        {% block table_item %}
            {{ block('item', 'areas/##AREALOWER##/##PREFIX##/_list_item.html.twig') }}
        {% endblock %}
    {% endcomponent %}
{% endblock %}