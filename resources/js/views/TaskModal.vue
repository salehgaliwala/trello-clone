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
                    <input type="hidden" v-model="data.board_id" class="form-control" id="exampleInputPassword1">
                <div class="form-group">
                    <textarea class="form-control" v-model="data.description" placeholder="description"></textarea>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" v-model="data.status" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1"> Completed ?</label>
                </div>
               <div class="modal-footer">
                     <button type="button" class="btn btn-primary" data-dismiss="modal" @click="closeModal">Save</button>
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
            props: ['task'],
            data() {
                return {
                }
            },
            computed: {
                data: function() {
                    if (this.task != null) {
                        return this.task
                    }
                    return {
                        title: "",
                        description: "",
                        board_id: "",
                        status: "",
                        column: "",
                    }
                }
            },
            methods: {
            closeModal(event){
                console.log("this.task: ",this.task);
                this.$emit('close', this.task)
            }
            }
        }
</script>