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
            'customYearCreateRoute'
        ],
        data() {
            return {
                selected: this.year,
            }
        },
        methods: {
            update() {
                if (this.selected === 'new') {
                    if (this.customYearCreateRoute) {
                        window.location.href = `/${this.resource}/${this.customYearCreateRoute}`;
                    } else {
                        window.location.href = `/${this.resource}/create`;
                    }

                } else {
                    window.location.href = `/${this.selected}/${this.resource}`;
                }
            }
        }
    }
</script>
