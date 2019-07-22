<template>
    <select class="custom-select w-auto" v-model="selected" @change="update">
        <option value="new">New</option>
        <option v-for="option in years" v-bind:key="option.id" :value="option">
            {{ option }}
        </option>
    </select>
</template>

<style scoped>
select {
    padding: 0.5rem 0.75rem 0.5rem 0.75rem;
}
</style>

<script>
    export default {
        props: [
            'year',
            'years',
            'resource',
        ],
        data() {
            return {
                selected: this.year,
            }
        },
        methods: {
            update() {
                console.log(this.resource)

                if (this.selected === 'new') {
                    console.log('redirect to new')
                } else {
                    console.log('redirect to ' + this.selected)
                }
            }
        }
    }
</script>
