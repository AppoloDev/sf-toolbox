{% extends '_layout/admin.html.twig' %}

{% block title %}Liste des __ROUTE_PATH__s{% endblock %}{# TODO: Wording #}

{% block breadcrumb %}
    {% with {items: [
        {path: path('##AREALOWER##_dashboard'), label: 'Accueil'},
        {path: null, label: block('title')},
    ]} %}
        {{ block('breadcrumb', '_shared/blocks/breadcrumb.html.twig') }}
    {% endwith %}
{% endblock %}

{% block body %}
    {% component table_list with {
        headerTitle: block('title'),
        tableColumns: [
            knp_pagination_sortable(pagination, 'ID', '##ENTITYLOWER##.id'), {# TODO: Implements #}
            ''
        ],
        pagination: pagination,
        emptyText: 'Essayez d\'ajouter un nouvel __ROUTE_PATH__s.', {# TODO: Wording #}
    } %}
        {% block header_actions %}
            <div class="flex gap-4 items-center">
                {{ component('button_action', {
                    permission: is_granted('##AREALOWER##_##PREFIX##_export'),
                    path: path('##AREALOWER##_##PREFIX##_export'), {# TODO: Mettre le query paremeters pour le querySearch #}
                    class: 'py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-{{themeColor}}-500 text-white hover:bg-{{themeColor}}-600 transition-all text-sm',
                    labelAfter: 'Exporter',
                    icon: source('@SFToolbox/icons/download.svg'),
                }) }}

                {{ component('button_action', {
                    permission: is_granted('##AREALOWER##_##PREFIX##_add'),
                    path: path('##AREALOWER##_##PREFIX##_add'),
                    class: 'py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-{{themeColor}}-500 text-white hover:bg-{{themeColor}}-600 transition-all text-sm',
                    labelAfter: 'Ajouter',
                    icon: source('@SFToolbox/icons/plus.svg'),
                }) }}
            </div>
        {% endblock %}

        {% block table_item %}
            {{ block('item', 'areas/##AREALOWER##/##PREFIX##/_list_item.html.twig') }}
        {% endblock %}
    {% endcomponent %}
{% endblock %}