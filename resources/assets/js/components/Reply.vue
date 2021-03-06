<template>
    <div :id="'reply-' + id" class="card" :class="isBest ? 'text-white bg-success' : ''">
        <div class="card-header" :class="isBest ? 'text-white bg-success' : ''">
            <div class="level">
                <span class="flex">
                    <a :class="isBest ? 'text-white font-weight-bold' : ''" href="'/profiles/' + reply.owner.name" title="Ver perfil"
                        v-text="reply.owner.name">
                    </a> said <span v-text="ago"></span>
                </span>

                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>
            </div>
        </div>

        <div class="card-body" :class="isBest ? 'bg-white text-dark' : ''">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <wysiwyg name="body" v-model="body"></wysiwyg>
                    </div>
                    <button class="btn btn-sm btn-primary">Update</button>
                    <button class="btn btn-sm btn-link" @click="editing = false" type="button">Cancel</button>
                </form>
            </div>

            <div v-else v-html="body"></div>
        </div>

        <div class="card-footer level"
            :class="isBest ? 'bg-light' : ''"
            v-if="authorize('owns', reply) || authorize('owns', thread)">

            <div v-if="authorize('owns', reply)">
                <button class="btn btn-sm mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-sm btn-danger mr-1" @click="destroy">Delete</button>
            </div>

            <button class="btn btn-sm btn-success ml-auto" @click="markBestReply"
                    v-if="authorize('owns', thread) && !isBest">

                Best Reply?
            </button>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';

    export default {
        props: ['reply'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                id     : this.reply.id,
                body   : this.reply.body,
                thread : window.thread
            };
        },

        computed: {
            isBest() {
                return this.thread.best_reply_id == this.id;
            },

            ago() {
                return moment(this.reply.created_at).fromNow() + '...';
            }
        },

        methods: {
            update () {
                axios.patch(
                    '/replies/' + this.id,  {
                        body: this.body
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });

                this.editing = false;

                flash('Updated!');
            },

            destroy() {
                axios.delete('/replies/' + this.id);

                this.$emit('deleted', this.id);

                flash('Your reply has been deleted.');
            },

            markBestReply() {
                axios.post('/replies/' + this.id + '/best');

                this.thread.best_reply_id = this.id;
            }
        }
    }
</script>
