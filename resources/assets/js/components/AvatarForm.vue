<template>
	<div>
		<div class="level">
        	<img class="mr-2 mb-2" :src="avatar" with="50" height="50">

			<h1 v-text="user.name"></h1>

			<small v-text="user.created_at">Since </small>
		</div>

    	<form v-if="canUpdate" method="post" enctype="multipart/form-data">
			<image-upload name="avatar" @loaded="onLoad"></image-upload>
    	</form>
    </div>
</template>

<script>
	import ImageUpload from './ImageUpload.vue';

	export default {
		props: ['user'],

		components: { ImageUpload },

		data() {
			return {
				avatar: this.user.avatar_path
			};
		},

		computed: {
			canUpdate() {
				return this.authorize(user => user.id === this.user.id);
			}
		},

		methods: {
			onLoad(avatar) {
				this.avatar = avatar.src;

				this.persist(avatar.file);
			},

			persist(avatar) {
				let data = new FormData();

				data.append('avatar', avatar);

				axios.post(`/api/users/${this.user.name}/avatar`, data)
					.then(() => flash('Avatar uploaded!'));
			}
		}
	}
</script>
