<template>
<div>
    
    
        <!-- Masthead -->
        <header class="masthead">

            <div class="boards-menu">

                <button class="boards-btn btn"><i class="fab fa-trello boards-btn-icon"></i>Boards</button>

                <div class="board-search">
                    <input type="search" class="board-search-input" aria-label="Board Search">
                    <i class="fas fa-search search-icon" aria-hidden="true"></i>
                </div>

            </div>

            <div class="logo">

                <h1><i class="fab fa-trello logo-icon" aria-hidden="true"></i>Bemo</h1>

            </div>

            <div class="user-settings">

                <button class="btn btn-secondary" @click="dumpDb()" aria-label="Dump Database">
                   Export
                </button>

                

            </div>

        </header>
        <!-- End of masthead -->


        <!-- Board info bar -->
        <section class="board-info-bar">

            <div class="board-controls">

                <button class="board-title btn">
                    <h2>Bemo Educational Consulting Projects</h2>
                </button>

                <button class="star-btn btn" aria-label="Star Board">
                    <i class="far fa-star" aria-hidden="true"></i>
                </button>


            </div>

        </section>
        <!-- End of board info bar -->

        <!-- Lists container -->
        <section  class="lists-container">

            <div class="list" v-for="(board, board_id) in boards" :key="board_id" >
            <button class="btn btn-primary" @click="deleteBoard(board.board_id)" aria-label="Dump Database">
                            Delete
            </button>
                <h3 class="list-title" >{{board.title}}</h3>
                <ul class="list-items" v-for="(task, index) in board.tasks" :key="index" @dblclick="editingItem = task">
                    <li  v-if="renderComponent" >{{task.title}}<span><img :src="image_src" @click="addColumn(task.task_id, 'up')" class="img-up"><img :src="image_src2" @click="addColumn(task.task_id, 'down')" class="img-down"></span></li>
                     
                </ul>
                     <modal @close="endEditing" :task="editingItem" v-show="editingItem != null"></modal>
                     <modal @close="addTask"  :task="addingTask" v-show="addingTask != null"></modal>
                <button class="add-card-btn btn"  @click="newTask(board.board_id)" >Add a card</button>
                
            </div>
              <newboard  @close="addNewBoard" :board="addingBoard"  v-show="addingBoard != null"></newboard>
            <button class="add-list-btn btn" @click="newBoard()">Adding a Board</button>
            
        </section>
        <!-- End of lists container -->

</div>




</template>

<script>
import Modal from './TaskModal'
import newboard from './NewBoard'

export default {
    data(){
        return {
            boards : [],
            editingItem: null,
            addingTask: null,
            addingBoard: null,
            index: 0,
            bdBoard:"",
            renderComponent: true,
            image_src: "https://images.vectorhq.com/images/previews/877/up-arrow-130418.png",
            image_src2: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQLWXgQ5fTSqlp4Ra1xNagYqmA8GdUCzMxNQQ&usqp=CAU"
        }
    },
    mounted(){
        axios.get("/api/boards/").then(response => {

           this.boards = response.data.data;
        }).catch(error => {
            console.log(error);
        });    
    },
    
    components: {Modal, newboard},
     methods: {
            newTask(board) {
                this.bdBoard = board;
                this.addingTask = {
                    title: null,
                    description: null,
                    board_id: null,
                    status: null,
                    column: null,
                }
                console.log(this.bdBoard);
                console.log(this.addingTask);
            },
            newBoard(){
                this.addingBoard = {
                    title: null,
                    description: null
                }
            },
            endEditing(task) {
                console.log(task);
                this.editingItem = null
                //let index = this.tasks.indexOf(task)
                let title = task.title
                let description = task.description
                let status = (task.status ? 0:1)
                //let column = task.column
                 console.log(JSON.stringify({title, description,  status}));
                axios.patch(`/api/tasks/${task.task_id}`, {title, description,  status})
                     .then(response => {
                     this.renderComponent = false;
                     this.$nextTick().then(() => {
                            // Add the component back in
                            this.renderComponent = true;
                            this.reloadComponent();
                        });
                    
                    }).catch(error => {
                        console.log(error);
                    });
            },
            addTask(task) {
                this.addingTask = null
               // console.log("board_id: ", this.bdBoard);
                let title = task.title
                let description = task.description
                let board_id = this.bdBoard
                let status = task.status
                let column = task.column

                axios.post("/api/tasks/", {title, description, board_id, status, column}).then(response => {
                        console.log("worked");
                            this.$nextTick(() => {
                            // Add the component back in
                            this.renderComponent = true;
                            this.reloadComponent();
                            });                 
                    }).catch(error => {
                        console.log(error);
                    });
            },
            addColumn(task_id, position){
                let column = position;
                console.log({task_id, position});

                axios.patch(`/api/tasks/column/${task_id}`, {column})
                     .then(response => {
                    this.renderComponent = false;
                            this.$nextTick(() => {
                            // Add the component back in
                            this.renderComponent = true;
                            this.reloadComponent();
                            });       
                    }).catch(error => {
                        console.log(error);
                    });
            },
            reloadComponent(){
                axios.get("/api/boards/").then(response => {
                    //console.log(response.data);
                this.boards = response.data.data;
                }).catch(error => {
                    console.log(error);
                }); 
            },
             addNewBoard(board){
                  
                this.addingBoard = null;  
                let  title =  board.title;
                 let description = board.description;
                 console.log({title, description});

                axios.post("/api/boards/", {title, description}).then(response => {
                    this.renderComponent = false;
                            this.$nextTick(() => {
                            // Add the component back in
                            this.renderComponent = true;
                            this.reloadComponent();
                            });  
                    }).catch(error => {
                        console.log(error);
                    });            
            },
            deleteBoard(board){
                axios.delete(`/api/boards/${board}`, {}).then(response => {
                    console.log(response);
                    this.renderComponent = false;
                            this.$nextTick(() => {
                            // Add the component back in
                            this.renderComponent = true;
                            this.reloadComponent();
                            });  
                    }).catch(error => {
                        console.log(error);
                    });
            },
            dumpDb(){
                    axios.post('/api/boards/dump/db', {responseType: 'arraybuffer'})
                    .then(response => {
                    let blob = new Blob([response.data])
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.download = 'data.sql'
                    link.click()
                    })
            }
        }
}
</script>