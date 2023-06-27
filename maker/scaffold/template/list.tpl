{% extends '_layout/admin.html.twig' %}

{% block title %}Liste des __ROUTE_PATH__s{% endblock %}{# TODO: Wording #}

{% block breadcrumb %}
    {% with {items: [
        {path: path('__LOWER_AREA___dashboard'), label: 'Accueil'},
        {path: null, label: block('title')},
    ]} %}
        {{ block('breadcrumb', '_shared/blocks/breadcrumb.html.twig') }}
    {% endwith %}
{% endblock %}

{% block body %}
    {% component table_list with {
        headerTitle: block('title'),
        tableColumns: [
            knp_pagination_sortable(pagination, 'ID', '__ALIAS__.id'), {# TODO: Implements #}
            ''
        ],
        pagination: pagination,
        emptyText: 'Essayez d\'ajouter un nouvel __ROUTE_PATH__s.', {# TODO: Wording #}
    } %}
        {% block header_actions %}
            <div class="flex gap-4 items-center">
                {{ component('button_action', {
                    permission: is_granted('__LOWER_AREA_____PREFIX___export'),
                    path: path('__LOWER_AREA_____PREFIX___export'), {# TODO: Mettre le query paremeters pour le querySearch #}
                    class: 'py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-teal-500 text-white hover:bg-teal-600 transition-all text-sm',
                    labelAfter: 'Exporter',
                    icon: source('@SFToolbox/icons/download.svg'),
                }) }}

                {{ component('button_action', {
                    permission: is_granted('__LOWER_AREA_____PREFIX___add'),
                    path: path('__LOWER_AREA_____PREFIX___add'),
                    class: 'py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-teal-500 text-white hover:bg-teal-600 transition-all text-sm',
                    labelAfter: 'Ajouter',
                    icon: source('@SFToolbox/icons/plus.svg'),
                }) }}
            </div>
        {% endblock %}

        {% block table_item %}
            {{ block('item', 'areas/__LOWER_AREA__/__PREFIX__/_list_item.html.twig') }}
        {% endblock %}
    {% endcomponent %}
{% endblock %}