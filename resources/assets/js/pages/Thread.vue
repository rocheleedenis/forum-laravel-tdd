<script>
	import Replies from '../components/Replies.vue';
	import SubscribeButton from '../components/SubscribeButton.vue';

	export default {
		components: { Replies, SubscribeButton },

		props: ['dataRepliesCount', 'dataLocked'],

		data() {
			return {
				repliesCount: this.dataRepliesCount,
				locked      : this.dataLocked
			}
		}
	}
</script>
