import TableOnSteroids from './TableOnSteroids.vue'

Statamic.booting(() => {
  Statamic.$components.register('table_on_steroids-fieldtype', TableOnSteroids)
})
