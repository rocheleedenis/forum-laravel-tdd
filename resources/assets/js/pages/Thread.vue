<script>
	import Replies from '../components/Replies.vue';
	import SubscribeButton from '../components/SubscribeButton.vue';

	export default {
		components: { Replies, SubscribeButton },

		props: ['thread'],

		data () {
			return {
				repliesCount: this.thread.replies_count,
				locked      : this.thread.locked,
				title       : this.thread.title,
				body        : this.thread.body,
				form        : {},
				editing     : false
			}
		},

		created () {
			this.resetForm();
		},

		methods: {
			toggleLock () {
				let url = `/locked-threads/${this.thread.slug}`;

				axios[this.locked ? 'delete' : 'post'](url);

				this.locked = ! this.locked;
			},

			update() {
				let url = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;

				axios.patch(url, this.form).then(() => {
					this.editing = false;

					this.title = this.form.title;
					this.body = this.form.body;

					flash('Your thread has been updated.');
				});
			},

			resetForm () {
				this.form = {
					title: this.thread.title,
					body : this.thread.body
				};

				this.editing = false;
			}
		}
	}
</script>
