<template>
  <div>
    <Table v-model="data" />
    <label class="form-group-label mt-2 mb-1">Paste your CSV into this field:</label>
    <textarea class="input-text w-full mb-2" v-model="csv" rows="4"></textarea>
    <button class="btn btn-default mb-2 mr-1" @click="csvToJavascript">Process CSV Data</button>
    <button class="btn btn-default mb-2" @click="handleClearInput">Clear input</button>
    <div class="text-red" v-if="error">{{ error }}</div>
  </div>
</template>
<script>

import parse from 'csv-parse/lib/sync'

const Table = Vue.component('table-fieldtype')

export default {
  mixins: [Fieldtype],
  data() {
    return {
      csv: '',
      data: [],
      error: '',
    }
  },
  components: {
    Table,
  },
  watch: {
    // safe guard if value prop changes from outside
    value: {
      deep: true,
      handler(data) {
        this.data = data;
      }
    },
    data: {
      deep: true,
      handler(data) {
        this.update(data);
      }
    }
  },
  created() {
    this.data = this.value;
  },
  methods: {
    handleClearInput() {
      this.csv = ''
    },
    csvToJavascript() {
      // parse entries from pasted csv
      this.error = ''
      try {
        const entries = parse(this.csv, {
          columns: true,
          skip_empty_lines: true,
        })
        var result = []
        const headerLine = {
          cells: [],
        }
        // save the keys as the first row
        Object.keys(entries[0]).forEach(key => headerLine.cells.push(key))
        result.push(headerLine)
        entries.forEach(entry => {
          const line = {
            cells: [],
          }
          for (const [key, value] of Object.entries(entry)) {
            line.cells.push(value)
          }
          result.push(line)
        })
        this.data = result
      } catch (e) {
        this.error = e.message
      }
    },
  },
}
</script>
