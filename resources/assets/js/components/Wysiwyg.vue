<template>
	<div>
		<input id="trix" type="hidden" :name="name" :value="value">

		<trix-editor input="trix" ref="trix"></trix-editor>
	</div>
</template>

<script>
	import Trix from 'trix';

	export default {
		components: { Trix },

		props: ['name', 'value'],

		mounted () {
			this.$refs.trix.addEventListener('trix-change', e => {
				this.$emit('input', e.target.innerHTML);
			});
		}
	}
</script>
