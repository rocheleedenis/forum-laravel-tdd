<template>
	<div>
		<div class="mx-3" v-if="signedIn">
            <div class="form-group row">
                <textarea name="body"
	                    class="form-control"
	                    placeholder="Have something to say?"
	                    rows="5"
	                    required
	                    v-model="body"
	                    id="body"></textarea>
            </div>
            <button type="submit"
	            	class="btn btn-default"
	            	style="margin: 0 -15px;"
	            	@click="addReply">Post</button>
		</div>
        <p v-else>
        	Please <a href="/login">sign in</a> to participate in this discussion.
        </p>
	</div>
</template>

<script>
	import 'jquery.caret';
	import 'at.js';

	export default {
		data() {
			return {
				body: ''
			};
		},

		computed: {
			signedIn() {
				return window.App.signedIn;
			}
		},

		mounted() {
			$('#body').atwho({
			    at: "@",
			    delay: 750,
			    callbacks: {
					remoteFilter: (query, callback) => {
						$.getJSON("/api/users", { name: query }, usernames => {
							callback(usernames)
						});
					}
				}
			});
		},

		methods: {
			addReply() {
				axios.post(location.pathname + '/replies', { body: this.body })
					.catch(error => {
						flash(error.response.data, 'danger');
					})
					.then(({data}) => {
						this.body = '';

						flash('Your reply has been posted.');

						this.$emit('created', data);
					});
			}
		}
	}
</script>
