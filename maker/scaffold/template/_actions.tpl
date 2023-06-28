{% block actions %}
    {% if is_granted('##AREALOWER##_##PREFIX##_edit', item) or is_granted('##AREALOWER##_##PREFIX##_delete', item) %}
        <div class="px-6 py-2">
            <div class="hs-dropdown relative inline-block [--placement:bottom-right]">
                <button id="hs-table-dropdown-6" type="button"
                        class="hs-dropdown-toggle py-1.5 px-2 inline-flex justify-center items-center gap-2 rounded-md text-gray-700 align-middle focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-{{themeColor}}-600 transition-all text-sm">
                    {{ source('@icons/ellipsis.svg') }}
                </button>
                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden mt-2 divide-y divide-gray-200 min-w-[10rem] z-20 bg-white shadow-2xl rounded-lg p-2 mt-2"
                     aria-labelledby="hs-table-dropdown-6">
                    <div class="py-2 first:pt-0 last:pb-0">
                                  <span class="block py-2 px-3 text-xs font-medium uppercase text-gray-400">
                                    Actions
                                  </span>
                        {{ component('button_action', {
                            permission: is_granted('##AREALOWER##_##PREFIX##_edit', item),
                            path: path('##AREALOWER##_##PREFIX##_add', {id: item.id}),
                            labelAfter: 'Modifier'
                        }) }}
                    </div>
                    {% if is_granted('##AREALOWER##_##PREFIX##_delete', item) %}
                    <div class="py-2 first:pt-0 last:pb-0">
                        {{ component('button_action', {
                            permission: is_granted('##AREALOWER##_##PREFIX##_delete', item),
                            path: path('##AREALOWER##_##PREFIX##_delete', {id: item.id}),
                            class: 'flex items-center gap-x-3 py-2 px-3 rounded-md text-sm hover:bg-gray-100 text-red-600',
                            labelAfter: 'Supprimer',
                            swal: {
                                title: 'Supprimer cet élément',
                                text: 'Vous êtes sur le point d’effectuer une action totalement irréversible …',
                                type: 'danger'
                            }
                        }) }}
                    </div>
                    {% endif %}
                </div>
            </div>
        </div>
    {% endif %}
{% endblock %}