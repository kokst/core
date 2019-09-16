<template>
    <selectize class="year-switcher w-auto" v-model="selected" @input="update" :settings="settings">
        <option v-if="disableYearCreateRoute !== true" value="new">{{ $t('vendor.kokst.core.components.yearswitcher.index.new') }}</option>
        <option v-for="option in years" v-bind:key="option.id" :value="option">
            {{ option }}
        </option>
    </selectize>
</template>

<style>
.year-switcher .selectize-input {
    min-width: 70px;
    display: inline-block;
}
</style>

<script>
    import Selectize from 'vue2-selectize'

    export default {
        components: {
            Selectize
        },
        props: [
            'year',
            'years',
            'namespace',
            'customYearCreateRoute',
            'disableYearCreateRoute',
        ],
        data() {
            return {
                selected: this.year,
                settings: {},
            }
        },
        methods: {
            update() {
                if (this.selected === 'new') {
                    if (this.customYearCreateRoute) {
                        window.location.href = `/${this.namespace}/${this.customYearCreateRoute}`;
                    } else {
                        window.location.href = `/${this.namespace}/create`;
                    }

                } else {
                    window.location.href = window.location.href.replace(this.year, this.selected);
                }
            }
        }
    }
</script>
