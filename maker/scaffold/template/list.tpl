{% extends '_layout/admin.html.twig' %}

{% block title %}{{'list_of_##ROUTEPATH##s'|trans}}{% endblock %}{# TODO: Translation #}

{% block breadcrumb %}
    <twig:Breadcrumb items="{{[
        {path: path('##AREALOWER##_dashboard'), label: 'home'|trans},
        {path: null, label: block('title')},
    ] }}"/>
{% endblock %}

{% block body %}
    {# TODO: Implements & wording #}
    {% component TableList with {
        headerTitle: block('title'),
        tableColumns: [
            knp_pagination_sortable(pagination, 'ID'|trans, '##ENTITYLOWER##.id'),
            ''
        ],
        pagination: pagination,
    } %}
        {% block header_actions %}
            <div class="flex gap-4 items-center">
                {% if is_granted('##AREALOWER##_##PREFIX##_export') %}
                    <twig:ButtonLink
                            link="{{ path('##AREALOWER##_##PREFIX##_export', {q: app.request.query.get('q')}) }}"
                            label="{{'export'|trans}}"
                            color="{{ themeColor }}"
                            mode="ghost"
                            icon="{{ ux_icon('fa6-solid:download') }}"
                    />
                {% endif %}

                {% if is_granted('##AREALOWER##_##PREFIX##_add') %}
                    <twig:ButtonLink
                            link="{{ path('##AREALOWER##_##PREFIX##_add') }}"
                            label="{{'add'|trans}}"
                            color="{{ themeColor }}"
                            icon="{{ ux_icon('fa6-solid:plus') }}"
                    />
                {% endif %}
            </div>
        {% endblock %}

        {% block emptyList %}
            {# TODO: Wording #}
            <twig:ListEmpty description="{{ 'try_to_add_a_new_item'|trans }}">
                <twig:block name="ListEmptyButton">
                    <twig:ButtonLink
                            allowDisplay="{{ is_granted('##AREALOWER##_##PREFIX##_add') }}"
                            icon="{{ ux_icon('fa6-solid:plus') }}"
                            label="{{'add'|trans}}"
                            link="{{ path('##AREALOWER##_##PREFIX##_add') }}"
                            color="{{ themeColor }}"
                    />
                </twig:block>
            </twig:ListEmpty>
        {% endblock %}

        {% block table_item %}
            {{ block('item', 'areas/##AREALOWER##/##PREFIX##/_list_item.html.twig') }}
        {% endblock %}
    {% endcomponent %}
{% endblock %}
