<template>
<div>
    <div class="modal modal-mask" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" v-html="data.title"></h5>
            <button type="button" class="close" data-dismiss="modal" @click="closeModal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <label for="exampleInputEmail1">Title</label>
                    <input type="text" v-model="data.title"  class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div class="form-group">
                     <label for="exampleInputEmail1">Board Description</label>
                    <textarea class="form-control" v-model="data.description" placeholder="description"></textarea>
                </div>
               
               <div class="modal-footer">
                     <button type="button" class="btn btn-primary" data-dismiss="modal" @click="closeModal()">Add</button>
               </div>
            </form>
        </div>
        
            </div>
        </div>
    </div>
</div>
    </template>


 <script>
        export default {
            props: ['board'],
            data() {
                return {
                }
            },
            computed: {
                data: function() {
                    if (this.board != null) {
                        return this.board
                    }
                    return {
                        title: "",
                        description: ""
                    }
                }
            },
            methods: {
             closeModal(event){
                 console.log("here: " + this.board);
                this.$emit('close', this.board );
            }
            }
        }
</script>