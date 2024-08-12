{% block actions %}
    {% if is_granted('##AREALOWER##_##PREFIX##_edit', item) or is_granted('##AREALOWER##_##PREFIX##_delete', item) %}
        <div class="px-6 py-2">
            <twig:DropdownMenu>
                <twig:block name="dropdownHeaderLabel">Actions</twig:block>
                <twig:block name="dropdownButton">
                    <twig:Button
                            icon="{{ ux_icon('fa6-solid:ellipsis') }}"
                            mode="white"
                            color="{{ themeColor }}"
                    />
                </twig:block>

                <twig:block name="dropdownItems">
                    <twig:DropdownItemContainer allowDisplay="{{ is_granted('##AREALOWER##_##PREFIX##_edit', item) }}">
                        <twig:block name="dropdownItemContainerItems">
                            <twig:ButtonLink
                                    allowDisplay="{{ is_granted('##AREALOWER##_##PREFIX##_edit', item) }}"
                                    link="{{ path('##AREALOWER##_##PREFIX##_edit', {id: item.id}) }}"
                                    label="Modifier"
                                    mode="ghost"
                                    :block="true"
                                    size="small"
                            />
                        </twig:block>
                    </twig:DropdownItemContainer>
                    <twig:DropdownItemContainer allowDisplay="{{ is_granted('##AREALOWER##_##PREFIX##_delete', item) }}">
                        <twig:block name="dropdownItemContainerItems">
                            <twig:ButtonLinkSweetAlert
                                    allowDisplay="{{ is_granted('##AREALOWER##_##PREFIX##_delete', item) }}"
                                    link="{{ path('##AREALOWER##_##PREFIX##_delete', {id: item.id}) }}"
                                    label="Supprimer"
                                    mode="ghost"
                                    :block="true"
                                    size="small"
                                    color="red"
                                    swalTitle="Supprimer cet élément"
                                    swalText="Vous êtes sur le point d’effectuer une action totalement irréversible …"
                                    swalColor="red"
                            />
                        </twig:block>
                    </twig:DropdownItemContainer>
                </twig:block>
            </twig:DropdownMenu>
        </div>
    {% endif %}
{% endblock %}
