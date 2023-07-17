{% block actions %}
    {% if is_granted('##AREALOWER##_##PREFIX##_edit', item) or is_granted('##AREALOWER##_##PREFIX##_delete', item) %}
        <div class="px-6 py-2">
            {{ component('dropdown_menu', {
                headerLabel: 'Actions',
                button: component('button', {
                    icon: source('@SFToolbox/icons/ellipsis.svg'),
                    mode: 'white',
                    color: themeColor
                }),
                items: [
                    component('dropdown_item_container', {
                        allowDisplay: is_granted('##AREALOWER##_##PREFIX##_edit', item),
                        items: [
                            component('button_link', {
                                allowDisplay: is_granted('##AREALOWER##_##PREFIX##_edit', item),
                                link: path('##AREALOWER##_##PREFIX##_add', {id: item.id}),
                                label: 'Modifier',
                                mode: 'ghost',
                                block: true,
                                size: 'small',
                            }),
                        ]
                    }),
                    component('dropdown_item_container', {
                        allowDisplay: is_granted('##AREALOWER##_##PREFIX##_delete', item),
                        items: [
                            component('button_link_sweetalert', {
                                allowDisplay: is_granted('##AREALOWER##_##PREFIX##_delete', item),
                                link: path('##AREALOWER##_##PREFIX##_delete', {id: item.id}),
                                label: 'Supprimer',
                                mode: 'ghost',
                                block: true,
                                size: 'small',
                                color: 'red',
                                swalTitle: 'Supprimer cet élément',
                                swalText: 'Vous êtes sur le point d’effectuer une action totalement irréversible …',
                                swalColor: 'red'
                            }),
                        ]
                    })
                ]
            }) }}
        </div>
    {% endif %}
{% endblock %}