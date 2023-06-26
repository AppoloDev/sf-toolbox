<div class="flex flex-col items-center p-6 lg:p-12">
    <div class="max-w-5xl w-full space-y-6 sm:space-y-12 divide-solid divide-y">
        <fieldset>
            <div class="space-y-2 mb-10">
                <p class="text-xl">Informations personnelles</p>
            </div>

            <div class="lg:flex gap-4 space-y-6 lg:space-y-0">
                <div class="w-full">
                    {# TODO: Implements #}
                </div>
            </div>
        </fieldset>

        <div class="pt-6 sm:pt-12">
            {{ component('form_submit', {
                form: form,
                deletePath: __ENTITYCAMEL__ is defined and is_granted('__LOWER_AREA_____PREFIX___delete', __ENTITYCAMEL__) ? path('__LOWER_AREA_____PREFIX___delete', {id: __ENTITYCAMEL__.id}) :null,
                swal: {
                title: 'Supprimer cet élément',
                text: 'Vous êtes sur le point d’effectuer une action totalement irréversible …',
                type: 'danger'
            }}) }}
        </div>
    </div>
</div>