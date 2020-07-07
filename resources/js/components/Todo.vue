<template>
  <div class="container">
    <div class="form-inline">
      <input
        type="text"
        placeholder="Add Todo"
        class="form-control add"
        v-model="todo.todo"
        @keydown.enter="createTodo"
      />
      <button class="btn btn-sm btn-success" @click="createTodo">Add Item</button>
    </div>
    <br />
    <br />
    <div class="list-group">
      <li
        class="list-group-item"
        v-for="todo in todos"
        :key="todo.id"
        :class="{done: todo.completed}"
      >
        <input type="checkbox" name="completed" v-model="todo.completed" id="completed" />
        {{ todo.todo }}
        <p class="float-right" @click="clean(todo)">X</p>
      </li>
      <br />
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      todos: [],
      todo: {
        todo: ""
      }
    };
  },
  methods: {
    fetchData() {
      axios
        .get("/api/todo")
        .then(res => {
          this.todos = res.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    createTodo() {
      axios.post("/api/todo", this.todo).then(res => {
        this.todos.unshift(res.data);
        this.todo.todo = "";
      });
    },
    completed(task) {
      axios
        .put(`api/todo/${todo.id}`, {
          completed: !todo.completed
        })
        .then(res => {
          console.log("todo updated!");
        })
        .catch(err => {
          console.log(err);
        });
    },
    clean(todo) {
      axios
        .delete(`/api/todo/${todo.id}`)
        .then(res => {
          const todoIndex = this.todos.indexOf(todo);
          this.todos.splice(todoIndex, 1);
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>

<style scoped>
.add {
  width: 80%;
  margin-right: 2rem;
}

.done {
  text-decoration: line-through;
  color: green;
}

.float-right {
  cursor: pointer;
}
</style>
