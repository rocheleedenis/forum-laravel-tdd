<template>
    <div :id="'reply-' + id" class="card" :class="isBest ? 'text-white bg-success' : ''">
        <div class="card-header" :class="isBest ? 'text-white bg-success' : ''">
            <div class="level">
                <span class="flex">
                    <a :class="isBest ? 'text-white font-weight-bold' : ''" href="'/profiles/' + data.owner.name" title="Ver perfil"
                        v-text="data.owner.name">
                    </a> said <span v-text="ago"></span>
                </span>

                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
                </div>
            </div>
        </div>

        <div class="card-body" :class="isBest ? 'bg-white text-dark' : ''">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-sm btn-primary">Update</button>
                    <button class="btn btn-sm btn-link" @click="editing = false" type="button">Cancel</button>
                </form>
            </div>

            <div v-else v-html="body"></div>
        </div>

        <div class="card-footer level" :class="isBest ? 'bg-light' : ''">
            <div v-if="authorize('updateReply', reply)">
                <button class="btn btn-sm mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-sm btn-danger mr-1" @click="destroy">Delete</button>
            </div>

            <button class="btn btn-sm btn-success ml-auto" @click="markBestReply" v-show="! isBest">Best Reply?</button>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';

    export default {
        props: ['data'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                id     : this.data.id,
                body   : this.data.body,
                reply  : this.data,
                thread : window.thread
            };
        },

        computed: {
            isBest() {
                return this.thread.best_reply_id == this.id;
            },

            ago() {
                return moment(this.data.created_at).fromNow() + '...';
            }
        },

        methods: {
            update () {
                axios.patch(
                    '/replies/' + this.data.id,  {
                        body: this.body
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });

                this.editing = false;

                flash('Updated!');
            },

            destroy() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);

                flash('Your reply has been deleted.');
            },

            markBestReply() {
                axios.post('/replies/' + this.data.id + '/best');

                this.thread.best_reply_id = this.id;
            }
        }
    }
</script>
