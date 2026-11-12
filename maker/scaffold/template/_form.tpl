<div class="flex flex-col items-center p-6 lg:p-12">
    <div class="max-w-5xl w-full space-y-6 sm:space-y-12">
        <fieldset>
            <div class="space-y-2 mb-10">
                <p class="text-xl">{{ 'information'|trans }}</p>
            </div>

            <div class="lg:flex gap-4 space-y-6 lg:space-y-0">
                <div class="w-full">
                    {# TODO: Implements #}
                </div>
            </div>
        </fieldset>

        <div class="pt-6 sm:pt-12 border-t">
            <twig:FormSubmit
                form="{{ form }}"
                deleteButtonLink="{{ ##ENTITYCAMEL## is defined and is_granted('##AREALOWER##_##PREFIX##_delete', ##ENTITYCAMEL##) ? path('##AREALOWER##_##PREFIX##_delete', {id: ##ENTITYCAMEL##.id}) :null }}"
                deleteSwalTitle="{{ 'delete_this_item'|trans }}"
                deleteSwalColor="red"
            />
        </div>
    </div>
</div>
