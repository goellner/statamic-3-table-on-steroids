import TableOnSteroids from './components/fieldtypes/TableOnSteroids.vue';

Statamic.booting(() => {
    Statamic.$components.register('table_on_steroids-fieldtype', TableOnSteroids);
});
