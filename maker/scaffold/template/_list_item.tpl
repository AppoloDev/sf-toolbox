{% block item %}
    <tr>
        <td class="h-px w-px whitespace-nowrap">
            <div class="px-6 py-3">
                <span class="block text-sm font-semibold text-gray-800">
                    {{ item.id }}
                </span>
                <span class="block text-sm text-gray-500">
                    Modifié le {{ item.updatedAt | date('d/m/Y') }}
                </span>
            </div>
        </td>

        <td class="h-px w-px whitespace-nowrap">
            <div class="px-6 py-1.5 flex justify-end">
                {{ block('actions', 'areas/##AREALOWER##/##PREFIX##/_actions.html.twig') }}
            </div>
        </td>
    </tr>
{% endblock %}