<template>
	<div>
		<div v-for="(reply, index, outro) in items">
			<reply :data="reply" @deleted="remove(index, outro)"></reply>
		</div>
	</div>
</template>

<script>
	import Reply from './Reply.vue';

	export default {
		props: ['data'],

		components: { Reply },

		data() {
			return {
				items: this.data
			}
		},

		methods: {
			remove(index, outro) {
				this.items.splice(index, 1);

				this.$emit('removed');

				flash('Your reply has been deleted.');
			}
		}
	}
</script>
